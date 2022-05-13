<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Contracts\ProductInterface;
use App\DataTransferObjects\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\UploadProductImageRequest;
use App\Http\Resources\ProductCollection;
use App\Jobs\UploadProductAndAttributeOptions;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Image;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use App\Services\Arr\ArrayServiceInterface;
use App\Services\Images\ImageServiceInterface;
use App\Services\Images\ImageToObjectArray;
use App\Services\Product\ProductServiceInterface;
use App\Services\Stripe\IStripeManager;
use Eav\AttributeOption;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller implements ProductInterface
{
    private $product;
    private $category;
    private $currency;
    private $productService;
    private $arrayService;
    private $imageService;
    public function __construct(Products $product, Category $category, Currency $currency, ProductServiceInterface $productService,ArrayServiceInterface $arrayService,ImageServiceInterface $imageService)
    {
        $this->product = $product;
        $this->category = $category;
        $this->currency = $currency;
        $this->productService = $productService;
        $this->arrayService = $arrayService;
        $this->imageService = $imageService;
    }
    /**
     * @OA\Get(
     *      path="/products",
     *      operationId="getProductsList",
     *      tags={"Products"},
     *      summary="Get list of products",
     *      description="Returns list of products",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Error",
     *      )
     *     )
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $seconds = 14400;
            $categories = Cache::remember('categories', $seconds, function () {
                return $this->category->select(['id','parent_id','name'])->orderBy('id')->limit(25)->get();
            });
            $currencies = Cache::remember('currencies', $seconds, function () {
                return $this->currency->get();
            });
            $products = $this->product->withAttributeOptions(['pr-price'])
                ->whereHas('productprice',function($query) {
                    $query->where('price','>',0);
                })
                ->orderBy('id')
                ->limit(100)
                ->get();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Error'],403);
        }
        return response()->json(['product'=>new ProductCollection($products),'crct'=>['currencies'=>$currencies,'categories'=>$categories]],200);
    }

    /**
     * @OA\Get(
     *      path="/products/{id}",
     *      operationId="getUserProductsList",
     *      tags={"Products"},
     *      summary="Get list of user products",
     *      description="Returns list of user products",
     *      @OA\Parameter(
     *         name="t",
     *         in="query",
     *         description="query - main or dsh.Example-api/v1/products/1?t=main or 1?t=dsh",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Product not found",
     *      )
     *     )
     */



    public function userProduct(int $id,Request $request) {
        if($request->t == 'main') {
            try {
                $product = $this->product->where('id',$id)
                    ->withAttributeOptions(['img','pr-info','pr-price'])
                    ->firstOrFail();
            }
            catch(ModelNotFoundException $exception) {
                return response()->json(['error'=>'Product not found'],403);
            }
            $this->productService->createViewLog($product->id);
            $abc = app(IStripeManager::class);
            $service = $abc->make('stripe');
            $search = 'metadata[\'product_id\']:'."'$id'";
            $productSearch = $service->productsSearch($search);
            if(auth()->user()) {
                $user = User::find(auth()->user()->id);
                $search1 = 'metadata[\'user_id\']:'."'$user->id'";
                $customer_id = $service->customerSearch($search1);
                $checkout = $customer_id->data ? $service->createCheckoutSession($customer_id,$id,$productSearch) : null;
            }
            else {
                $checkout = null;
            }
        }
        if($request->t == 'dsh') {
            try {
                $builder = $this->product->whereUser($id)
                    ->withAttributeOptions(['pr-price']);
                $product = $builder->getOrFail();
            }
            catch(ModelNotFoundException $exception) {
                return response()->json(['error'=>'User not found'],403);
            }
            $checkout = null;
        }
        return response()->json(['product'=>$product,'stripe'=>$checkout],200);
    }

    /**
     * @OA\Post(
     *      path="/products/create",
     *      operationId="storeCoupon",
     *      tags={"Products"},
     *      summary="Store new produt",
     *      description="Create new product",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function store(ProductStoreRequest $request)
    {
        $validated = ProductData::fromRequest($request);
        $options = $this->arrayService->makeOptionArray($validated);
        DB::beginTransaction();
        try {
            $id = DB::table('products')->insertGetId([
                'entity_id' => 1,
                'attribute_set_id' => 1
            ]);
        }
        catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }
        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $stripe->products->create([
                'name' =>(string)$validated->name,
                'metadata' => [
                    'price'=>(int)$validated->price,
                    'user_id'=>auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,
                ],
            ]);
        }
        catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }
        try {
            $categories = explode(",",$validated->category);
            foreach($categories as $category_id) {
                ProductCategory::create([
                    'product_id'=>$id,
                    'category_id'=>$category_id
                ]);
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }
        try {
            ProductPrice::create([
               'id'=>$id,
               'price'=>$validated->price,
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }
        try {
            UploadProductAndAttributeOptions::dispatch($id, $options)->afterCommit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return response()->json(['status'=>'добавлен успешно'],201);
    }

    /**
     * @OA\Post(
     *      path="/products/image/{product_id}",
     *      operationId="storeProductImage",
     *      tags={"Products"},
     *      summary="Store new product image",
     *      description="Create new product image",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function uploadProductImage(UploadProductImageRequest $request,int $id) {
        $file = $this->imageService->InvertionImage($request->file('file'));
        $files = $this->imageService->InvertionImages($request->file('files'));
        Image::create([
           'images'=>  ImageToObjectArray::make($files),
           'product_id'=>$id
        ]);
        AttributeOption::where(['product_id'=>$id,'attribute_id'=>6])->update(['value' => $file]);
        return response()->json(['status'=>'Успешно загружены фотографии'],201);
    }

    /**
     * @OA\Delete(
     *      path="/products/delete",
     *      operationId="deleteProduct",
     *      tags={"Products"},
     *      summary="Delete existing product",
     *      description="Delete a productt",
     *      @OA\Parameter(
     *         name="pr",
     *         in="query",
     *         description="Products ids",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
    *       )
     */

    public function delete(Request $request) {
        $this->productService->productArrayDelete(explode(",", $request->pr));
        return response()->json(['status'=>'Успешно удалено'],200);
    }

    /**
     * @OA\Get(
     *      path="/brands_categories",
     *      operationId="getBrandsAndCategoriesList",
     *      tags={"BrandsAndCategories"},
     *      summary="Get list of brands and categories",
     *      description="Returns list of brands and categories",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Нет таких записей",
     *      )
     *     )
     */


    public function brandsandcategories() {
        $seconds = 14400;
        try {
            $categories = Cache::remember('categories', $seconds, function () {
                return DB::table('categories')->orderBy('id')->select(['id', 'name'])->get();
            });
            $brands = Cache::remember('brands', $seconds, function () {
                return DB::table('brands')->orderBy('id')->select(['id', 'name'])->get();
            });
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Нет таких записей'],403);
        }
        $crct = ['categories'=>$categories,'brands'=>$brands];
        return response()->json(['crct'=>$crct],200);
    }

}
