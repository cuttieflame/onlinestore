<?php

namespace App\Http\Controllers\API;

use App\Contracts\FavoriteInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Favorite;
use App\Products;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class FavoriteController extends Controller implements FavoriteInterface
{
    /**
     * @param $id
     * @return false|JsonResponse
     */
    public function userFavorites($id = null): bool|JsonResponse
    {

        $user_id = $id ?: (auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null);

        if(is_null($user_id)) {
            return false;
        }
        $favorites = Favorite::with(["product"])->where(["user_id" => $user_id])->get();
        return response()->json($favorites,200);
    }

    /**
     * @OA\Get(
     *      path="/favorite",
     *      operationId="getUserFavoritesList",
     *      tags={"Favorites"},
     *      summary="Get list of user favorites",
     *      description="Returns list of user favorites",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Favorite not found",
     *      )
     *     )
     */

    public function get(): JsonResponse
    {

        $products = Favorite::with(['product' => function ($q) {
            $q->withAttributeOptions(['pr-price']);
        }])
            ->where(["session_id" => session()->getId()])
            ->get();
        if($products->isEmpty()) {
            try {
                $products = Favorite::with(['product' => function ($q) {
                    $q->withAttributeOptions(['pr-price']);
                }])
                    ->where(["user_id" => 145])
                    ->getOrFail();
            }
            catch(ModelNotFoundException) {
                return response()->json(['status'=>'Ошибка'],403);
            }
        }

        return response()->json(['products'=>new CartCollection($products)],200);
    }
    /**
     * @OA\Post(
     *      path="/favorite/add/{product_id}",
     *      operationId="storeNewFavoriteProduct",
     *      tags={"Favorites"},
     *      summary="Store new cart product",
     *      description="Store new cart product",
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

    public function add(int $product_id): JsonResponse
    {
        try {
            $product = Products::where('id', $product_id)
                ->with(['productprice'])
                ->firstOrFail();
        }
        catch(ModelNotFoundException) {
            return response()->json(['status'=>'Нет такого продукта'],403);
        }
        if($cart = Favorite::where([
            "session_id" => session()->getId(),
            "product_id" => $product->id
        ])->first()) {
            $cart->increment("quantity");
            $cart->save();
        } else {
            Favorite::create([
                "session_id" => session()->getId(),
                "product_id" => $product->id,
                "user_id" => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,
            ]);
        }
        return response()->json(['status'=>'Успешно добавлено в favorites'],201);
    }

    /**
     * @OA\Delete  (
     *      path="/favorite/delete/{product_id}",
     *      operationId="FavoriteDeleteProduct",
     *      tags={"Favorites"},
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


    public function delete(int $id): JsonResponse
    {
        try {
            Favorite::where([
                'session_id'=>session()->getId(),
                'product_id'=>$id,
            ])->orWhere([
                'user_id'=>auth()->user()->id,
                'product_id'=>$id
            ])->firstOrFail()->delete();
        }
        catch(ModelNotFoundException) {
            return response()->json(['status'=>'Нет избранных товаров'],403);
        }
        return response()->json(['status'=>'Товар успешно удален из корзины'],200);

    }

    /**
     * @OA\Post   (
     *      path="/favorite/clear",
     *      operationId="FavoriteClear",
     *      tags={"Favorites"},
     *      summary="favorite clear",
     *      description="Favorite clear",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Cart error",
     *      )
     *     )
     */


    public function clear(): JsonResponse
    {
        try {
            $favorite = Favorite::select(['id','session_id'])
                ->where(["session_id" => session()->getId()])->getOrFail()->each->delete();
        }
        catch(ModelNotFoundException) {
            return response()->json(['status'=>'Нет записей в корзине для удаления'],403);
        }
        return response()->json(['status'=>'Корзина очищена'],200);
    }

    /**
     * @OA\Post   (
     *      path="/favorite/total",
     *      operationId="FavoriteTotal",
     *      tags={"Favorites"},
     *      summary="favorite total",
     *      description="Favorite total",
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

    public function total(): JsonResponse
    {
        try {
            $favorite = Favorite::select(['id','price','quantity'])->getOrFail();
        }
        catch(ModelNotFoundException) {
            return response()->json(['status'=>'Cart error'],403);
        }
        $sum = $favorite->map(function ($item) {
            return $item->price * $item->quantity;
        })->sum();
        return response()->json(['total'=>$sum],200);
    }
}
