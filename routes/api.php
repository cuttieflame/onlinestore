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

    Route::prefix('user')->group(function () {
        Route::get('/',[UserController::class,'index']);
        Route::delete('/destroy/{id}',[UserController::class,'destroy']);
        Route::put('/update/{id}',[UserController::class,'update']);
        Route::post('/updateImage/{id}',[UserController::class,'updateImage']);
    });

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'userProduct']);
    Route::post('/products/create', [ProductController::class, 'store'])->middleware(['perm:create-products']);
    Route::post('products/delete',[ProductController::class,'delete']);
    Route::post('products/image/{product_id}',[ProductController::class,'uploadProductImage'])->middleware(['perm:create-products']);

    Route::prefix('cart')->group(function () {
        Route::get('/',[CartController::class,'get']);
        Route::post('/cart/add/{product_id}',[CartController::class,'add']);
        Route::put('/quantity',[CartController::class,'quantity']);
        Route::post('/clear',[CartController::class,'flush']);
        Route::delete('/delete/{product_id}',[CartController::class,'remove']);
    });

    Route::prefix('coupons')->group(function () {
        Route::get('/',[CouponController::class,'index']);
        Route::post('/make',[CouponController::class,'store']);
        Route::get('/{coupon}',[CouponController::class,'changeCurrency']);
    });

    Route::post('/order/make/{id}',[OrderController::class,'makeOrder']);

    Route::prefix('favorite')->group(function () {
        Route::get('/',[FavoriteController::class,'get']);
        Route::post('/add/{product_id}',[FavoriteController::class,'add']);
        Route::post('/clear',[FavoriteController::class,'flush']);
        Route::delete('/delete/{product_id}',[FavoriteController::class,'remove']);
    });

    Route::post('password/password-reset',[UserController::class,'resetPassword']);
    Route::get('email/resend',[VerificationController::class,'sendVerificationEmail'])->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])->name('verification.verify');

    Route::get('currency/{currencyCode}',[CurrencyController::class,'changeCurrency']);
    Route::get('/currentValues',[CurrencyController::class,'current']);

    Route::get('/category/{id?}', [CategoryController::class, 'index']);

    Route::prefix('stripe')->group(function () {
        Route::get('/',[SubscriptionController::class,'index']);
        Route::post('/webhook',[SubscriptionController::class,'webhook']);
        Route::get('/allproducts',[SubscriptionController::class,'getAllProducts']);
        Route::post('/add/product',[SubscriptionController::class,'addProduct']);
        Route::post('/add/customer/{id}',[SubscriptionController::class,'addCustomer']);
        Route::get('/products/{id}',[SubscriptionController::class,'getProducts']);
    });
    Route::get('/brands_categories',[ProductController::class,'brandsandcategories']);
    Route::get('/user/subscriptions',[SubscriptionController::class,'getUserSubscriptions']);

    Route::get('test',[TestController::class,'index']);



