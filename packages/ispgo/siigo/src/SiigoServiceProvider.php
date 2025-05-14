<?php
namespace Ispgo\Siigo;

use Illuminate\Support\ServiceProvider;
use Ispgo\Siigo\Listeners\{SyncCustomer, SyncInvoice};
use Illuminate\Support\Facades\Event;

class SiigoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/siigo.php', 'siigo');

        $this->app->singleton(SiigoClient::class, function ($app) {
            return new SiigoClient(config('siigo'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/siigo.php' => config_path('siigo.php'),
        ], 'config');

        Event::listen(\App\Events\CustomerCreated::class, SyncCustomer::class);
        Event::listen(\App\Events\InvoiceCreated::class,  SyncInvoice::class);
    }
}
