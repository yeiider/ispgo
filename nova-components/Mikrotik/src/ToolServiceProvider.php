<?php

namespace Ispgo\Mikrotik;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;

/**
 * Service Provider para el módulo Mikrotik
 * 
 * NOTA: Este módulo ya no registra listeners automáticos para eventos de servicio.
 * La provisión de servicios (bind IP + create queue) se realiza manualmente
 * a través de la API GraphQL o las acciones de Nova.
 * 
 * Flujo de trabajo:
 * 1. El usuario crea un servicio
 * 2. El usuario consulta los DHCP leases disponibles via GraphQL
 * 3. El usuario selecciona una IP y MAC y ejecuta la provisión via GraphQL
 * 4. El sistema amarra la IP y crea el Simple Queue
 */
class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Ya no registramos listeners automáticos
        // La provisión se hace manualmente via GraphQL

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
