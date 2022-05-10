<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\Product;
use App\Models\User;
use App\Observers\UserObserver;
use App\Providers\RepositoryServiceProvider;
use App\Services\Arr\ArrayService;
use App\Services\Arr\ArrayServiceInterface;
use App\Services\Date\DateInterface;
use App\Services\Date\DateService;
use App\Services\Images\ImageService;
use App\Services\Images\ImageServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\Test\DemoOne;
use App\Services\Test\DemoOneInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Sanctum::usePersonalAccessTokenModel(
            PersonalAccessToken::class
        );
        $this->app->bind(\App\Services\Stripe\IStripeManager::class, function ($app) {
            return new \App\Services\Stripe\StripeManager($app);
        });
        $this->app->bind(\App\Services\Order\IOrderManager::class, function ($app) {
            return new \App\Services\Order\OrderManager($app);
        });
        $this->app->bind(DemoOneInterface::class,function($app) {
           return new DemoOne();
        });
        $this->app->bind(ArrayServiceInterface::class, ArrayService::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(DateInterface::class, DateService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
//        $this->app->bind(ArrayServiceInterface::class,function($app) {
//           return new ArrayService();
//        });

    }
    public function boot()
    {

        User::observe(UserObserver::class);
//        JsonResource::withoutWrapping();

//        Product::observe(ProductObserver::class);
    }
}
