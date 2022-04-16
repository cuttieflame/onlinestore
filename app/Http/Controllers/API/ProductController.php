<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
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
use App\Services\ImageToObjectArray;
use Eav\AttributeOption;
use Illuminate\Http\Request;
use Illuminate\Container\Container;

class ProductController extends Controller
{
//    private $brand;
//    public function __construct(Brand $brand)
//    {
//        $this->brand = $brand;
//    }
//    public function get() {
//        return $this->brand->get();
//    }


    public function index(Request $request)
    {
        $brands = \DB::table('brands')->select(['id','title'])->get();
        $categories = \Cache::remember('categories', '14400', function () {
            return Category::select(['id','parent_id','title'])->orderBy('id')->limit(25)->get();
        });
        $currencies = \Cache::remember('currencies', '14400', function () {
            return Currency::get();
        });
        $a = Products::withAttributeOptions(['pr-price'])
                ->orderBy('id')
                ->limit(100)
                ->get();

        $crct = ['currencies'=>$currencies,'categories'=>$categories];
        return response()->json(['product'=>$a,'crct'=>$crct]);

    }
    public function myProducts($id,Request $request) {
        if($request->t == 'main') {
            $a = Products::where('id',$id)
                ->withAttributeOptions(['img','pr-info','pr-price'])
                ->first();
            $product_id = $a->id;
                PostView::createViewLog($product_id);
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
            $a = Products::whereUser($id)
                ->withAttributeOptions(['pr-price'])
                ->get();
            $checkout = null;
        }
        return response()->json(['product'=>$a,'stripe'=>$checkout]);
    }
    public function store(ProductStoreRequest $request)
    {
        $options = ArrayOptionService::makeOptionArray($request);
        \DB::beginTransaction();
        try {
            $id = \DB::table('products')->insertGetId([
                'entity_id' => 1,
                'attribute_set_id' => 1
            ]);
            $product = Products::find($id);
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
            $pr_ctg->product_id = $id;
            $pr_ctg->category_id = $request->input('category');

            $pr_ctg->save();
        }
        catch(\Exception $e) {
            \DB::rollback();
            throw $e;
        }
        try {
            $images = new Image();
            $images->images = ImageToObjectArray::make($request->input('images'));
            $images->product_id = $id;
            $images->save();
        }
        catch(\Exception $e)
        {
            \DB::rollback();
            throw $e;
        }
        try {
            $pr_price = new ProductPrice();
            $pr_price->id = $id;
            $pr_price->price = $request->input('price');
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

    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {

    }
}
