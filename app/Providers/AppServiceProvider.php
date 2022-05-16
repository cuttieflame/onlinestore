<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\Product;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Products;
use App\Providers\RepositoryServiceProvider;
use App\Services\Arr\ArrayService;
use App\Services\Arr\ArrayServiceInterface;
use App\Services\Date\DateServiceInterface;
use App\Services\Date\DateService;
use App\Services\Images\ImageService;
use App\Services\Images\ImageServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\Stripe\IStripeManager;
use App\Services\Stripe\StripeManager;
use App\Services\Test\DemoOne;
use App\Services\Test\DemoOneInterface;
use App\Services\Test\DemoTwo;
use App\Services\Test\DemoTwoInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Sanctum::usePersonalAccessTokenModel(
            PersonalAccessToken::class
        );
        $this->app->bind(IStripeManager::class, function ($app) {
            return new StripeManager($app);
        });
        $this->app->bind(\App\Services\Order\IOrderManager::class, function ($app) {
            return new \App\Services\Order\OrderManager($app);
        });
//        $this->app->bind(DemoOneInterface::class,function($app) {
//           return new DemoOne();
//        });
        $this->app->singleton(DemoOneInterface::class,function($app) {
            return new DemoOne();
        });
        $this->app->singleton(DemoTwoInterface::class,function($app) {
            return new DemoTwo();
        });
        $this->app->bind(ArrayServiceInterface::class, ArrayService::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(DateServiceInterface::class, DateService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
//        $this->app->bind(ArrayServiceInterface::class,function($app) {
//           return new ArrayService();
//        });

    }
    public function boot()
    {

        User::observe(UserObserver::class);
        Products::observe(ProductObserver::class);
//        JsonResource::withoutWrapping();

//        Product::observe(ProductObserver::class);
    }
}
