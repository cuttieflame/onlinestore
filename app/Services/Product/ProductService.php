<?php

namespace App\Services\Product;

use App\Models\ProductView;
use App\Products;

final class ProductService implements ProductServiceInterface
{
    public function createViewLog(int $product_id) {
        ProductView::create([
            'product_id'=>$product_id,
            'titleslug'=>'slug',
            'url'=>request()->url(),
            'session_id'=>auth()->user() ? request()->getSession()->getId() : 'no session',
            'user_id'=>auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,
            'ip'=>request()->ip(),
            'agent'=>request()->header('User-Agent')
        ]);
    }
    public function productArrayDelete(array $arr) {
        foreach($arr as $id) {
            Products::where('id',$id)->delete();
        }
    }
}