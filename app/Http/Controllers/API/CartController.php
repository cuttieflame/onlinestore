<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contracts\CartInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Jobs\CartQuantity;
use App\Products;
use Illuminate\Http\Request;
use App\Models\Cart;
use Carbon\Carbon;

class CartController extends Controller implements CartInterface
{
    public function userCarts($id = null) {
        $user_id = $id ? $id : (auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null);

        if(is_null($user_id)) {
            return false;
        }

        return Cart::with(["product"])->where(["user_id" => $user_id])->get();
    }
    public function get() {
        $products = Cart::with(['product' => function ($q) {
            $q->withAttributeOptions(['pr-price']);
        }])
//            ->where(["session_id"=>"rhVxbdVkfejke30fm4gx7NKuw42YYlOGZK2kqTci"])
            ->where(["session_id" => session()->getId()])
            ->get();
        $times = ["now"=>Carbon::now()->format('d.m.Y'),"not_now"=>Carbon::now()->addDays(2)->format('d.m.Y')];

   return response()->json(['products'=>new CartCollection($products),'times'=>$times],200);
    }
    public function add($product_id) {
        $product = Products::where('id',$product_id)
            ->with(['productprice'])
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
                "price" => $product->productprice->price,
            ]);
        }
        return response()->json(['status'=>'Успешно добавлена'],200);
    }
    public function quantity(Request $request) {
        $cart = Cart::select(['id','quantity'])->where('id',$request->cart_id)->first();
        CartQuantity::dispatch($cart,$request->quantity,$request->value);
    }
    public function remove($id) {
        return Cart::destroy($id);
    }
    public function flush() {
        Cart::where(["session_id" => session()->getId()])->delete();
        return response()->json(['error'=>'Корзина очищена'],200);
    }
    public function total() {
        return Cart::get()->map(function ($item) {
            return $item->price * $item->quantity;
        })->sum();
    }
}
