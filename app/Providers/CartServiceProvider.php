<?php

namespace App\Providers;

use App\Models\CartManager;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    protected $namespace = "cart";

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton($this->namespace, CartManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $path = __DIR__ . "/..";

        /* Config */
        $this->publishes([
            $path . '/config/' . $this->namespace . '.php' => config_path($this->namespace . '.php'),
        ]);

        /* Migrations */
        $this->loadMigrationsFrom($path . '/database/migrations');
    }

}
