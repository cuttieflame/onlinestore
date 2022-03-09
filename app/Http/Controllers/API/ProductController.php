<?php

namespace App\Http\Controllers\API;

use App\Events\PostHasViewed;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\CurrencyRates;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function current() {
        CurrencyRates::getRates();
    }
    public function index()
    {
        $currencies = \Cache::remember('currencies', '14400', function () {
            return $this->productRepository->getCurrenciesForProducts();
        });
        $brands = \Cache::remember('brands', '14400', function () {
            return $this->productRepository->getBrands();
        });
        $categories = \Cache::remember('categories', '14400', function () {
            return $this->productRepository->getCategories();
        });
       $products = $this->productRepository->getProducts(100);
       $product = $products->sortBy('id')->values()->slice(0,8);
        $recently_viewed = $products->sortBy('view_count')->values()->slice(0,8);
        $combined = $product->merge($recently_viewed);
        foreach($combined as $product) {
            $product['categories'] = $categories[$product->category_id]->title;
            $product['brands'] = $brands[$product->brand_id - 1]->title;
        }
        $arr_ip = geoip()->getLocation(\Request::ip());
        return response()->json(['combined'=>$combined,'currencies'=>$currencies,'categories'=>$categories]);

    }
    public function create($id)
    {
        $product = Product::where('id',$id)
            ->select(
                ['id','name','content','view_count','main_image',
                'images','price','category_id','brand_id',
                'product_id','rating','order_count','name_attributes',
                'attribute_info'])
                ->join('products_info', 'products_info.product_id', '=', 'products.id')
            ->first();
        return response()->json(['product'=>new ProductResource($product)]);
    }
    public function myProducts($id) {
        $my_product = $this->productRepository->myProducts($id);
        return response()->json(['product'=>$my_product]);
    }
    public function store(Request $request)
    {
        $user = new Product();
        $user->name = $request->input('name');
        $user->content = $request->input('content');
        $user->main_image = $request->input('main_image');
        $user->images = $request->input('images');
        $user->price = $request->input('price');
        $user->category_id = $request->input('category');
        $user->brand_id = $request->input('brand');
        $user->save();
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
