<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Contracts\CategoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Products;
use App\Services\Arr\ArrayServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


/**
 *
 */
class CategoryController extends Controller implements CategoryInterface
{
    /**
     * @var Products
     */
    private Products $product;
    /**
     * @var Brand
     */
    private Brand $brand;
    /**
     * @var Category
     */
    private Category $category;
    /**
     * @var ArrayServiceInterface
     */
    private ArrayServiceInterface $arrayService;

    /**
     * @param Products $product
     * @param Brand $brand
     * @param Category $category
     * @param ArrayServiceInterface $arrayService
     */
    public function __construct(Products $product, Brand $brand, Category $category, ArrayServiceInterface $arrayService)
    {
        $this->product = $product;
        $this->brand = $brand;
        $this->category = $category;
        $this->arrayService = $arrayService;
    }

    /**
     * @OA\Get(
     *      path="/category/{id}",
     *      operationId="getCategories",
     *      tags={"Category"},
     *      summary="Get list of categories",
     *      description="Returns list of categories",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id,if id = 0, then it is needed for the search,if id > 0 its for categories",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *         name="br",
     *         in="query",
     *         description="brands,example ?br=1,2,3,4 ",
     *      ),
     *      @OA\Parameter(
     *         name="o",
     *         in="query",
     *         description="parameters (pr-in|pr-de), it means that it sorts products by price increase, if pr-de, then vice versa by decrease",
     *      ),
     *     @OA\Parameter(
     *         name="s",
     *         in="query",
     *         description="parameters (new|old) if (new) sorts by novelty and if (old) by old age",
     *      ),
     *     @OA\Parameter(
     *         name="min",
     *         in="query",
     *         description="min price,example ?min=123",
     *      ),
     *     @OA\Parameter(
     *         name="max",
     *         in="query",
     *         description="max price,example ?max=5000",
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
     *          description="Category not found",
     *      )
     *     )
     */


    public function index(Request $request,$id = 0): JsonResponse
    {
        if($id != 0) {
            $subbrands =  $this->brand->select(['id','name','category_id','categories'])->get();
            $brands = $this->arrayService->makeBrandArray($subbrands,preg_replace('/\D+/', '', $id));
            $builder = $this->product->withAttributeOptions(['pr-price','pr-ctgrs'])
                ->join('product_categories', 'product_categories.product_id', '=', 'products.id')
                ->where('product_categories.category_id',preg_replace('/\D+/', '', $id));

            $ctgr = $this->arrayService->makeRelatedCategories($builder);
            $rld_itms = array_unique($ctgr[1]);
            $related_items = $this->category->whereIn('id',$rld_itms)->where('parent_id',null)
                ->with('childrencategories')
                ->get();

//            $max = max($ctgr[0]);
//            $min = min($ctgr[0]);
//
//            $avg = (min($ctgr[0]) + max($ctgr[0])) * 0.5;

            if ($request->has('br') or $request->has('min') or $request->o !== "undefined" and $request->o !== null or $request->has('s') or in_array($request->o, ['pr-in', 'pr-de'])) {

                if($request->br) {
                    $builder->whereHas('productcategories',function($query) use ($request) {
                        $query->whereIn('category_id',explode(",", $request->br));
                    });
                }
                if($request->min and $request->max) {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->where([
                            ['product_prices.price', '>', $request->min],
                            ['product_prices.price', '<', $request->max],
                        ]);
                }
                if ($request->o == 'pr-in') {
                    $builder->orderPrices(0);
                }
                if($request->o == 'pr-de') {
                    $builder->orderPrices(1);
                }
                if($request->s == 'new') {
                    $builder->orderBy('id');
                }
                if($request->s == 'old') {
                    $builder->orderBy('id','desc');
                }
            }
            $products = $builder->limit(200)->paginate(10);
        }
        if($id == 0) {
            $query = preg_match("/^[a-zа-яA-ZА-ЯёЁ]/u", $request->search);
            if($query == '' or $query == null) {
                return response()->json(['status'=>'Введите правильный запрос'],400);
            }
            $builder = Products::withAttributeOptions(['pr-price','pr-ctgrs'])
                ->whereHas('attributesoptions',function($q) use ($query) {
                    $q->where([
                        ['attribute_id', '=', 4],
                        ['value', 'like', "%{$query}%"],
                    ])->orWhere([
                        ['attribute_id', '=', 5],
                        ['value', 'like', "%{$query}%"],
                    ]);
                });
            $ctgr = $this->arrayService->makeRelatedCategories($builder);

//            $avg = (min($ctgr[0]) + max($ctgr[0])) * 0.5;
            if ($request->has('br') or $request->has('pr') or $request->o !== "undefined" and $request->o !== null or $request->has('s') or in_array($request->o, ['pr-in', 'pr-de'])) {

                if($request->br) {
                    $builder->whereHas('productcategories',function($query) use ($request) {
                        $query->whereIn('category_id',explode(",", $request->br));
                    });
                }
                if($request->pr and $request->max) {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->where([
                            ['product_prices.price', '>', $request->pr],
                            ['product_prices.price', '<', $request->max],
                        ]);
                }
                if ($request->o == 'pr-in') {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->orderBy('product_prices.price','ASC');
                }
                if($request->o == 'pr-de') {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->orderBy('product_prices.price','DESC');
                }
                if($request->s == 'new') {
                    $builder->orderBy('id');
                }
                if($request->s == 'old') {
                    $builder->orderBy('id','desc');
                }
            }

            $subbrands = $this->brand->select(['id','name','category_id','categories'])->get();
            $brands = $this->arrayService->makeBrandArray($subbrands,1);
            $related_items = $this->category->whereIn('id',array_unique($ctgr[1]))->get();
            $products = $builder->limit(200)->paginate(10);

        }
        return response()->json(['products'=>$products,'related_items'=>$related_items,'brands'=>$brands],200);
    }

}
