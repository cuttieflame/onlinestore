<?php

use App\Http\Controllers\API\BrandsAndCategoriesController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VerificationController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('user',[UserController::class,'index']);
Route::delete('/user/destroy/{id}',[UserController::class,'destroy']);
Route::put('/user/update/{id}',[UserController::class,'update']);
Route::get('/brands_categories',[BrandsAndCategoriesController::class,'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/my/product/{id}', [ProductController::class, 'myProducts']);
Route::post('/products/create', [ProductController::class, 'store']);

Route::get('/all/products',function() {
   return response()->json(\DB::table('products')->select(['id','name','content','main_image','images','category_id','brand_id','price'])->latest()->first());
});
Route::get('/cart',[CartController::class,'get']);
Route::post('/cart/add/{product_id}',[CartController::class,'add']);
Route::put('/cart/quantity/{cart_id}/{quantity}/{value}',[CartController::class,'quantity']);
Route::post('/cart/clear',[CartController::class,'flush']);
Route::delete('/cart/delete/{product_id}',[CartController::class,'remove']);

Route::get('/favorite',[FavoriteController::class,'get']);
Route::post('/favorite/add/{product_id}',[FavoriteController::class,'add']);
Route::post('/favorite/clear',[FavoriteController::class,'flush']);
Route::delete('/favorite/delete/{product_id}',[FavoriteController::class,'remove']);

Route::post('password/password-reset',[UserController::class,'resetPassword']);
Route::get('email/resend',[VerificationController::class,'sendVerificationEmail'])->name('verification.resend');
Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])->name('verification.verify');

Route::get('currency/{currencyCode}',[CurrencyController::class,'changeCurrency']);
Route::get('/currentValues',[ProductController::class,'current']);
Route::get('/product/{id}',[ProductController::class,'create']);
Route::get('/category/{id}', [CategoryController::class, 'index']);




