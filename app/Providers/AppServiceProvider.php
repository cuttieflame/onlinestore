<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\Product;
use App\Models\User;
use App\Observers\UserObserver;
use App\Providers\RepositoryServiceProvider;
use App\Services\Test\DemoOne;
use App\Services\Test\DemoOneInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
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
//        $this->app->register(RepositoryServiceProvider::class);

    }
    public function boot()
    {

        User::observe(UserObserver::class);
//        JsonResource::withoutWrapping();

//        Product::observe(ProductObserver::class);
    }
}
