<?php

namespace Ispgo\Wiivo;

use Illuminate\Support\ServiceProvider;
use Ispgo\Wiivo\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

class WiivoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register any bindings or configurations here
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::middleware([Authorize::class])
            ->prefix('wiivo/api/')
            ->group(__DIR__ . '/../routes/api.php');
    }

}
