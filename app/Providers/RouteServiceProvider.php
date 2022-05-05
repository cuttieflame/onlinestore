<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    // protected $namespace = 'App\\Http\\Controllers';
    protected $apiNamespace ='App\Http\Controllers\API';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api/v1')
                ->middleware(['api','api_version:v1'])
                ->namespace($this->apiNamespace)
                ->group(base_path('routes/api.php'));

            Route::prefix('api/v2')
                ->middleware(['api','api_version:v2'])
                ->namespace($this->apiNamespace)
                ->group(base_path('routes/api.php'));

//            Route::middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
