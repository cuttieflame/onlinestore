<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contracts\CartInterface;
use App\Exceptions\CartSessionNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Jobs\CartQuantity;
use App\Products;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $carts = Cart::with(["product"])->where(["user_id" => $user_id])->get();
        return response()->json($carts,200);
    }

    /**
     * @OA\Get(
     *      path="/cart",
     *      operationId="getUserCartList",
     *      tags={"Carts"},
     *      summary="Get list of user cart",
     *      description="Returns list of user cart",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Cart not found",
     *      )
     *     )
     */

    public function get() {
        try {
            $products = Cart::with(['product' => function ($q) {
                $q->withAttributeOptions(['pr-price']);
            }])
                ->where(["session_id" => session()->getId()])
                ->getOrFail();
        }
        catch(CartSessionNotFoundException $exception) {
            $products = Cart::with(['product' => function ($q) {
                $q->withAttributeOptions(['pr-price']);
            }])
                ->where(["user_id" => auth()->user()->id])
                ->getOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Ошибка'],403);
        }
        $times = ["now"=>Carbon::now()->format('d.m.Y'),"not_now"=>Carbon::now()->addDays(2)->format('d.m.Y')];

   return response()->json(['products'=>new CartCollection($products),'times'=>$times],200);
    }

    /**
     * @OA\Post(
     *      path="/cart/add/{product_id}",
     *      operationId="storeNewCartProduct",
     *      tags={"Carts"},
     *      summary="Store new produt",
     *      description="Create new product",
     *      @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         description="Product id",
     *         required=true,
     *         @OA\Schema(
     *              type="integer"
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Product not found"
     *      )
     * )
     */


    public function add(int $product_id) {
        try {
            $product = Products::where('id', $product_id)
                ->with(['productprice:id,price'])
                ->firstOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Нет такого продукта'],403);
        }
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
                "price" => $product->productprice->price ? $product->productprice->price : 0,
            ]);
        }
        return response()->json(['status'=>'Успешно добавлена'],201);
    }

    /**
     * @OA\Put (
     *      path="/cart/quantity",
     *      operationId="CartAddProduct",
     *      tags={"Carts"},
     *      summary="cart add product",
     *      description="Cart add product",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Cart not found",
     *      )
     *     )
     */

    public function quantity(Request $request) {
        try {
            $cart = Cart::select(['id','quantity'])->where('id',$request->cart_id)->firstOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Корзина не найдена'],403);
        }
        CartQuantity::dispatch($cart,$request->quantity,$request->value);
        return response()->json(['status'=>'Изменено количество'],200);
    }

    /**
     * @OA\Delete  (
     *      path="/cart/delete/{product_id}",
     *      operationId="CartDeleteProduct",
     *      tags={"Carts"},
     *      summary="cart delete product",
     *      description="Cart delete product",
     *      @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         description="Product id for delete",
     *         required=true,
     *         @OA\Schema(
     *              type="integer"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Cart not found",
     *      )
     *     )
     */


    public function delete(int $id) {
        try {
            $cart = Cart::where([
                'session_id'=>session()->getId(),
                'product_id'=>$id,
                ])->firstOrFail()->delete();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Корзины не существует'],403);
        }
       return response()->json(['status'=>'Товар успешно удален из корзины'],200);
    }

    /**
     * @OA\Post   (
     *      path="/cart/clear",
     *      operationId="CartClear",
     *      tags={"Carts"},
     *      summary="cart clear",
     *      description="Cart clear",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Cart error",
     *      )
     *     )
     */

    public function clear() {
        try {
            $cart = Cart::select(['id','session_id'])
                ->where(["session_id" => session()->getId()])->getOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Нет записей в корзине для удаления'],403);
        }
        $cart->delete();
        return response()->json(['status'=>'Корзина очищена'],200);
    }

    /**
     * @OA\Post   (
     *      path="/cart/total",
     *      operationId="CartTotal",
     *      tags={"Carts"},
     *      summary="cart total",
     *      description="Cart total",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Cart error",
     *      )
     *     )
     */


    public function total() {
        try {
            $cart = Cart::select(['id','price','quantity'])->getOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Cart error'],403);
        }
        $sum = $cart->map(function ($item) {
            return $item->price * $item->quantity;
        })->sum();
        return response()->json(['total'=>$sum],200);
    }
}
