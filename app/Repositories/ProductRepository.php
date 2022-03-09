<?php

namespace App\Repositories;

use App\Models\Product;
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
       return Product::select(['id','name','brand_id','category_id','view_count','content','price','main_image'])
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
}
