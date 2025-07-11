<?php

namespace Ispgo\NapManager;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Ispgo\NapManager\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->routes();
        });

        // Load migrations from the package
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish migrations if needed
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'nap-manager-migrations');

        Nova::serving(function (ServingNova $event) {
            // Register Nova resources
            Nova::resources([
            ]);
        });
    }

    /**
     * Register the tool's routes.
     */
    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', 'nova.auth', Authorize::class], 'nap-manager')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', 'nova.auth', Authorize::class])
            ->prefix('nova-vendor/nap-manager')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
