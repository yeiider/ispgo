<?php

namespace Ispgo\Siigo\Listeners;

use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SyncCustomer
{
    public function handle($event): void
    {
        if (!ConfigProviderSiigo::getEnabled())
            return;

        if (ConfigProviderSiigo::getSyncCustomer()) {
            $customer = $event->customer;
            $trigger = ConfigProviderSiigo::getSyncCustomersTrigger();

            $shouldSync = ($trigger === 'all') || ($customer->taxDetails && $customer->taxDetails->enable_billing);

            if ($shouldSync) {
                $job = new CreateSiigoCustomer($customer);
                dispatch($job)->delay(now()->addSeconds(10))->onQueue('redis');
            }
        }
    }
}
