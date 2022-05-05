<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CentrifugaController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ParserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TestController;
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
    Route::post('/user/updateImage/{id}',[UserController::class,'updateImage']);

    Route::get('/brands_categories',[ProductController::class,'brandsandcategories']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'userProduct']);
    Route::post('/products/create', [ProductController::class, 'store'])->middleware(['perm:create-products']);
    Route::post('products/delete',[ProductController::class,'delete']);
    Route::post('products/image/{product_id}',[ProductController::class,'uploadProductImage'])->middleware(['perm:create-products']);

    Route::get('/cart',[CartController::class,'get']);
    Route::post('/cart/add/{product_id}',[CartController::class,'add']);
    Route::put('/cart/quantity',[CartController::class,'quantity']);
    Route::post('/cart/clear',[CartController::class,'flush']);
    Route::delete('/cart/delete/{product_id}',[CartController::class,'remove']);

    Route::post('/order/make/{id}',[OrderController::class,'makeOrder']);
    Route::get('/coupons/',[CouponController::class,'index']);
    Route::post('/coupons/make',[CouponController::class,'store']);
    Route::get('/coupons/{coupon}',[CouponController::class,'changeCurrency']);

    Route::get('/favorite',[FavoriteController::class,'get']);
    Route::post('/favorite/add/{product_id}',[FavoriteController::class,'add']);
    Route::post('/favorite/clear',[FavoriteController::class,'flush']);
    Route::delete('/favorite/delete/{product_id}',[FavoriteController::class,'remove']);

    Route::post('password/password-reset',[UserController::class,'resetPassword']);
    Route::get('email/resend',[VerificationController::class,'sendVerificationEmail'])->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])->name('verification.verify');

    Route::get('currency/{currencyCode}',[CurrencyController::class,'changeCurrency']);
    Route::get('/currentValues',[CurrencyController::class,'current']);
    Route::get('/category/{id?}', [CategoryController::class, 'index']);

    Route::get('/stripe',[SubscriptionController::class,'index']);
    Route::post('/stripe/webhook',[SubscriptionController::class,'webhook']);
    Route::get('/stripe/allproducts',[SubscriptionController::class,'getAllProducts']);
    Route::get('/stripe/add/product',[SubscriptionController::class,'addProduct']);
    Route::get('/stripe/add/customer/{id}',[SubscriptionController::class,'addCustomer']);
    Route::get('/stripe/products/{id}',[SubscriptionController::class,'getProducts']);
    Route::post('/stripe/webhook',[SubscriptionController::class,'webhook']);

    Route::get('/user/subscriptions',[SubscriptionController::class,'getUserSubscriptions']);

    Route::get('test',[TestController::class,'index']);



