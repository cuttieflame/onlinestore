<?php

namespace App\Repositories;

use App\Models\Product;
use App\Products;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getCurrenciesForProducts() {
        return \DB::table('currencies')
            ->select(['id','code','symbol','rate'])
            ->get();
    }
    public function getBrands() {
        return \DB::table('brands')->select(['id','title'])->get();
    }
    public function getCategories() {
        return \DB::table('categories')->select(['id','title'])->get();
    }
    public function getProducts($limit) {
       return Product::select(['id','name','brand_id','category_id','view_count','content','price','discount','main_image'])
           ->limit($limit)
           ->orderBy('id')
           ->get();
    }
    public function myProducts($id) {
        return Product::where('user_id',$id)
        ->select(['id','name','brand_id','category_id','view_count','content','price','main_image'])
            ->with('brands','categories')
            ->orderBy('id')
            ->get();
    }

    public function search(string $query = '')
    {      return Products::with(['attributesoptions' => function ($q) {
            $q->select(['attribute_id','value','product_id'])->orderBy('attribute_id');
            }])
            ->with('productprice')
            ->whereHas('attributesoptions',function($q) use ($query) {
            $q->where([
                ['attribute_id', '=', 4],
                ['value', 'like', "%{$query}%"],
            ])->orWhere([
                ['attribute_id', '=', 5],
                ['value', 'like', "%{$query}%"],
            ]);
            })
            ->paginate(10);
    }
}
