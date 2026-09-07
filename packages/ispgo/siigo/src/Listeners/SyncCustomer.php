<?php

namespace Ispgo\Siigo\Listeners;

use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SyncCustomer
{
    public function handle($event): void
    {
        $customer = $event->customer;
        $scopeId = (int) ($customer->router_id ?? 0);

        if (!ConfigProviderSiigo::getEnabled($scopeId))
            return;

        if (ConfigProviderSiigo::getSyncCustomer($scopeId)) {
            $trigger = ConfigProviderSiigo::getSyncCustomersTrigger($scopeId);

            $shouldSync = ($trigger === 'all') || ($customer->taxDetails && $customer->taxDetails->enable_billing);

            if ($shouldSync) {
                $job = new CreateSiigoCustomer($customer);
                dispatch($job)->delay(now()->addSeconds(10))->onQueue('redis');
            }
        }
    }
}
