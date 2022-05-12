<?php

namespace App\Http\Controllers\API;

use App\Contracts\FavoriteInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\User;
use App\Products;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FavoriteController extends Controller implements FavoriteInterface
{
    public function userFavorites($id = null) {

        $user_id = $id ? $id : (auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null);

        if(is_null($user_id)) {
            return false;
        }
        $favorites = Favorite::with(["product"])->where(["user_id" => $user_id])->get();
        return response()->json($favorites,200);
    }
    public function get() {
        $products = Favorite::with(['product' => function ($q) {
            $q->withAttributeOptions(['pr-price']);
        }])
            ->where(["session_id" => session()->getId()])
            ->get();
        return response()->json(['products'=>new CartCollection($products)],200);
    }
    public function add(int $product_id) {
        $product = Products::where('id',$product_id)
            ->with(['productprice'])
            ->first();
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
        return response()->json(['status'=>'Успешно добавлено в favorites'],200);
    }
    public function remove($id) {
        Favorite::destroy($id);
        return response()->json(['status'=>'Успешно удалена запись'],200);
    }
    public function flush() {
        Favorite::where(["session_id" => session()->getId()])->delete();
        return response()->json(['error'=>'Список избранных очищена'],200);
    }
    public function total() {
        return Favorite::get()->map(function ($item) {
            return $item->price * $item->quantity;
        })->sum();
    }
}
