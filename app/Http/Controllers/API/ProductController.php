<?php

namespace App\Http\Controllers\API;

use App\Contracts\ProductInterface;
use App\DataTransferObjects\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Currency;
use App\Models\PostView;
use App\Jobs\UploadProductAndAttributeOptions;
use App\Models\Category;
use App\Models\Image;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use App\Services\ArrayOptionService;
use App\Services\ImageService;
use App\Services\ImageToObjectArray;
use Eav\AttributeOption;
use Illuminate\Http\Request;
use Illuminate\Container\Container;

class ProductController extends Controller implements ProductInterface
{
    private $product;
    public function __construct(Products $product)
    {
        $this->product = $product;
    }
    public function index()
    {
        $brands = \DB::table('brands')->select(['id','name'])->get();
        $categories = \Cache::remember('categories', '14400', function () {
            return Category::select(['id','parent_id','name'])->orderBy('id')->limit(25)->get();
        });
        $currencies = \Cache::remember('currencies', '14400', function () {
            return Currency::get();
        });
        $a = $this->product->withAttributeOptions(['pr-price'])
                ->orderBy('id')
                ->limit(100)
                ->get();

        $crct = ['currencies'=>$currencies,'categories'=>$categories];
        return response()->json(['product'=>new ProductCollection($a),'crct'=>$crct]);

    }
    public function userProduct(int $id,Request $request) {
        if($request->t == 'main') {
            $a = $this->product->where('id',$id)
                ->withAttributeOptions(['img','pr-info','pr-price'])
                ->first();
                PostView::createViewLog($a->id);
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $search = 'metadata[\'product_id\']:'."'$id'";
            $pr = $stripe->products->search([
                'query' => $search,
            ])['data'];
            $user = User::find(3);
            $search1 = 'metadata[\'user_id\']:'."'$user->id'";
            $customer_id = $stripe->customers->search([
                'query' => $search1,
            ]);
            $checkout = $stripe->checkout->sessions->create([
                'customer'=>$customer_id['data'][0]->id,
                'success_url' => 'http://localhost:3000/dashboard/subscription/success',
                'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
                'metadata' => [
                    'currency' => $pr[0]['metadata']['currency'],
                    'price'=>$pr[0]['metadata']['price'],
                    'name'=>$pr[0]['name'],
                    'product_id'=>$id
                ],
                'line_items' => [               [
                    'price_data'=> [
                        'currency'=>$pr[0]['metadata']['currency'],
                        'unit_amount'=>$pr[0]['metadata']['price'] * 100,
                        'product_data'=>[
                            'name'=>$pr[0]['name'],
                        ],
                    ],
                    'quantity'=>1,
                ],
                ],
                'mode' => 'payment',
            ]);
        }
        if($request->t == 'dsh') {
            $builder = $this->product->whereUser($id)
                ->withAttributeOptions(['pr-price']);
            if($request->cp == 0) {

            }
            $a = $builder->get();
            $checkout = null;
        }
        return response()->json(['product'=>$a,'stripe'=>$checkout]);
    }
    public function store(ProductStoreRequest $request)
    {
        $validated = ProductData::fromRequest($request);
        $options = ArrayOptionService::makeOptionArray($validated);
        \DB::beginTransaction();
        try {
            $id = \DB::table('products')->insertGetId([
                'entity_id' => 1,
                'attribute_set_id' => 1
            ]);
            $product = $this->product->find($id);
            activity()
                ->performedOn($product)
                ->causedBy(auth()->user()->id)
                ->event('create')
                ->inLog('create')
                ->withProperties(['user' => auth()->user()->id,'product'=>$id])
                ->log('create product');
        }
        catch(\Exception $e) {
            \DB::rollback();
            throw $e;
        }
        try {
            $pr_ctg = new ProductCategory();
            $b = explode(",",$validated->category);
            foreach($b as $elem) {
                $pr_ctg->product_id = $id;
                $pr_ctg->category_id = $elem;
                $pr_ctg->save();
            }
        }
        catch(\Exception $e) {
            \DB::rollback();
            throw $e;
        }
//        try {
//            $images = new Image();
//            $images->images = ImageToObjectArray::make($validated->images);
//            $images->product_id = $id;
//            $images->save();
//        }
//        catch(\Exception $e)
//        {
//            \DB::rollback();
//            throw $e;
//        }
        try {
            $pr_price = new ProductPrice();
            $pr_price->id = $id;
            $pr_price->price = $validated->price;
            $pr_price->save();
        }
        catch(\Exception $e)
        {
            \DB::rollback();
            throw $e;
        }
        try {
            UploadProductAndAttributeOptions::dispatch($id, $options)->afterCommit();
        }
        catch(\Exception $e)
        {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();
        return response()->json(['product_id'=>$id]);
    }
    public function uploadProductImage(Request $request,int $id) {
        $file = $request->file('file');
        $files = $request->file('files');
        $a = ImageService::InvertionImage($file);
        $b = ImageService::InvertionImages($files);
        $images = new Image();
        $images->images = ImageToObjectArray::make($b);
        $images->product_id = $id;
        $images->save();
        $atr_options = AttributeOption::where(['product_id'=>$id,'attribute_id'=>6])->first();
        $atr_options->value = $a;
        $atr_options->save();
    }
    public function delete(Request $request) {
        $a =  explode(",", $request->pr);
        foreach($a as $elem) {
           Products::where('id',$elem)->delete();
        }
        return response()->json($request);
    }

}
