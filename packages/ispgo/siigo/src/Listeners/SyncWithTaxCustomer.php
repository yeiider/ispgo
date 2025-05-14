<?php

namespace Ispgo\Siigo\Listeners;

use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SyncWithTaxCustomer
{
    public function handle(\App\Events\TaxCustomerCreated $event): void
    {
        if (!ConfigProviderSiigo::getEnabled())
            return;

        if (ConfigProviderSiigo::getSyncCustomer() && ConfigProviderSiigo::getSyncCustomersTrigger() !== 'all') {
            $job = new CreateSiigoCustomer($event->taxDetail->customer);
            dispatch($job)->delay(now()->addSeconds(10))->onQueue('redis');
        }
    }
}
