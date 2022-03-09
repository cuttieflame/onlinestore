<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public static function userFavorites($id = null) {
        $user_id = $id ? $id : (auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null);

        if(is_null($user_id)) {
            return false;
        }

        return Favorite::with(["product"])->where(["user_id" => $user_id])->get();
    }

    public static function get() {
        $products = Favorite::with(['product:id,name,content,view_count,main_image,price'])
            ->where(["session_id" => session()->getId()])
            ->get();
        return response()->json(['products'=>$products]);
//      return response()->json(['products'=>new CartResource($products)]);
    }
    public static function add($product_id) {
        $product = Product::findOrFail($product_id);

        if($cart = Favorite::where([
            "session_id" => session()->getId(),
            "product_id" => $product->id
        ])->first()) {
            $cart->increment("quantity");
            $cart->save();
        } else {
            $cart = Favorite::create([
                "session_id" => session()->getId(),
                "product_id" => $product->id,
                "user_id" => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,
            ]);
        }
        return $cart;
    }
    public static function remove($id) {
        return Favorite::destroy($id);
    }
    public static function flush() {
        return Favorite::where(["session_id" => session()->getId()])->delete();
    }
    public static function total() {
        return Favorite::get()->map(function ($item) {
            return $item->price * $item->quantity;
        })->sum();
    }
}
