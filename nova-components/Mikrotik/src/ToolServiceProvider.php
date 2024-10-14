<?php

namespace Ispgo\Mikrotik;

use App\Events\ServiceCreated;
use App\Events\ServiceUpdateStatus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ispgo\Mikrotik\Listener\ServiceChangeStatus;
use Ispgo\Mikrotik\Listener\ServiceCreateListener;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Ispgo\Mikrotik\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Event::listen(
            ServiceCreated::class,
            [ServiceCreateListener::class, 'handle']
        );
        Event::listen(
            ServiceUpdateStatus::class,
            [ServiceChangeStatus::class, 'handle']
        );


        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Lang::setLocale(config('app.locale'));
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authenticate::class, Authorize::class], 'mikrotik')
            ->group(__DIR__ . '/../routes/inertia.php');

        Route::prefix('mikrotik')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('translator', function ($app) {
            return $app->make('Illuminate\Contracts\Translation\Translator');
        });
    }
}
