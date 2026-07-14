<?php

namespace Ispgo\Siigo\Listeners;

use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SyncWithTaxCustomer
{
    public function handle($event): void
    {
        if (!ConfigProviderSiigo::getEnabled())
            return;

        if (ConfigProviderSiigo::getSyncCustomer()) {
            $customer = $event->taxDetail->customer;
            if ($customer && ($customer->taxDetails && $customer->taxDetails->enable_billing)) {
                $job = new CreateSiigoCustomer($customer);
                dispatch($job)->delay(now()->addSeconds(10))->onQueue('redis');
            }
        }
    }
}
