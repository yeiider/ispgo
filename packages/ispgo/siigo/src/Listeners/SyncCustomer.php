<?php

namespace Ispgo\Siigo\Listeners;

use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SyncCustomer
{
    public function handle(\App\Events\CustomerCreated $event): void
    {
        if (!ConfigProviderSiigo::getEnabled())
            return;

        if (ConfigProviderSiigo::getSyncCustomer() && ConfigProviderSiigo::getSyncCustomersTrigger() === 'all') {
            $job = new CreateSiigoCustomer($event->customer);
            dispatch($job)->delay(now()->addSeconds(10))->onQueue('redis');
        }
    }
}
