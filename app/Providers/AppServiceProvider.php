<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;
use App\Models\Product;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use App\Providers\RepositoryServiceProvider;

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
//        $this->app->register(RepositoryServiceProvider::class);
    }
    public function boot()
    {
        User::observe(UserObserver::class);
//        JsonResource::withoutWrapping();

//        Product::observe(ProductObserver::class);
    }
}
