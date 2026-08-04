<?php
namespace Ispgo\Siigo;

use Illuminate\Support\ServiceProvider;
use Ispgo\Siigo\Listeners\{SyncCustomer, SyncInvoice, SyncWithTaxCustomer};
use Illuminate\Support\Facades\Event;

class SiigoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/siigo.php', 'siigo');

        $this->app->singleton(SiigoClient::class, function ($app) {
            $config = [
                'enabled'     => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getEnabled() ?? config('siigo.enabled', false),
                'environment' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getEnvironment() ?? config('siigo.environment', 'prod'),
                'base_url'    => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getBaseUrl() ?? config('siigo.base_url', 'https://siigo.com/'),
                'username'    => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getUsername() ?? config('siigo.username'),
                'access_key'  => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getAccessKey() ?? config('siigo.access_key'),
                'partner_id'  => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getPartnerId() ?? config('siigo.partner_id'),
            ];
            return new SiigoClient($config);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/siigo.php' => config_path('siigo.php'),
        ], 'config');

        Event::listen(\App\Events\CustomerCreated::class, SyncCustomer::class);
        Event::listen(\App\Events\CustomerUpdated::class, SyncCustomer::class);
        Event::listen(\App\Events\TaxCustomerCreated::class, SyncWithTaxCustomer::class);
        Event::listen(\App\Events\TaxCustomerUpdated::class, SyncWithTaxCustomer::class);
        Event::listen(\App\Events\InvoiceCreated::class, [SyncInvoice::class, 'onCreated']);
        Event::listen(\App\Events\InvoicePaid::class, [SyncInvoice::class, 'onPaid']);
        Event::listen(\App\Events\InvoiceCanceled::class, [SyncInvoice::class, 'onCanceled']);
    }
}
