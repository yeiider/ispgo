<?php

namespace Ispgo\Smartolt;


use App\Events\ServiceActive;
use App\Events\ServiceSuspend;
use App\Events\ServiceUpdateStatus;
use App\Nova\Actions\Service\ActivateService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ispgo\Smartolt\Listeners\ServiceOltManagerListener;
use Ispgo\Smartolt\Listeners\ServiceOltManagerListenerActive;
use Ispgo\Smartolt\Listeners\ServiceOltManagerListenerSuspend;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Ispgo\Smartolt\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Event::listen(
            ServiceUpdateStatus::class,
            [ServiceOltManagerListener::class, 'handle']
        );
       /** Event::listen(
            ServiceSuspend::class,
            [ServiceOltManagerListenerSuspend::class, 'handle']
        );
        Event::listen(
            ServiceActive::class,
            [ServiceOltManagerListenerActive::class, 'handle']
        );**/


        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::mix('smart-olt', __DIR__.'/../dist/mix-manifest.json');
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

        Nova::router(['nova', Authenticate::class, Authorize::class], 'smartolt')
            ->group(__DIR__ . '/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/smartolt')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
