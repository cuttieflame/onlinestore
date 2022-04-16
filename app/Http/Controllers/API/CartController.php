<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Jobs\CartQuantity;
use App\Products;
use Illuminate\Http\Request;
use App\Models\Cart;
use Carbon\Carbon;

class CartController extends Controller
{
    public static function userCarts($id = null) {
        $user_id = $id ? $id : (auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null);

        if(is_null($user_id)) {
            return false;
        }

        return Cart::with(["product"])->where(["user_id" => $user_id])->get();
    }

    public static function get() {
        $products = Cart::with(['product'])
            ->where(["session_id" => session()->getId()])
            ->get();
        $times = ["now"=>Carbon::now()->format('d.m.Y'),"not_now"=>Carbon::now()->addDays(2)->format('d.m.Y')];
   return response()->json(['products'=>$products,'times'=>$times]);
//      return response()->json(['products'=>new CartResource($products)]);
    }
    public static function add($product_id) {
        $product = Products::where('id',$product_id)
            ->with(['attributesoptions' => function ($query) {
                $query->select(['attribute_id','value','product_id'])->where('attribute_id',3);
            }])
            ->first();

        if($cart = Cart::where([
            "session_id" => session()->getId(),
            "product_id" => $product->id
        ])->first()) {
            $cart->increment("quantity");
            $cart->save();
        }
        else {
            $cart = Cart::create([
                "session_id" => session()->getId(),
                "product_id" => $product->id,
                "user_id" => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,
                "price" => $product->attributesoptions[0]->value,
            ]);
        }
        return $cart;
    }
    public static function quantity($id, $quantity,$value) {
        $cart = Cart::select(['id','quantity'])->findOrFail($id);
        CartQuantity::dispatch($cart,$quantity,$value);
    }
    public static function remove($id) {
        return Cart::destroy($id);
    }
    public static function flush() {
        return Cart::where(["session_id" => session()->getId()])->delete();
    }
    public static function total() {
        return Cart::get()->map(function ($item) {
            return $item->price * $item->quantity;
        })->sum();
    }
}
