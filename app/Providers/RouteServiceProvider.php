<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            // ensure api routes load
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
