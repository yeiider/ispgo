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
            $taxDetail = $event->taxDetail ?? null;
            // Ignore events triggered only by updating Siigo sync metadata
            if ($taxDetail && $taxDetail->wasChanged(['siigo_customer_id', 'siigo_synced_at']) && count($taxDetail->getChanges()) <= 2) {
                return;
            }

            $customer = $taxDetail?->customer;
            if ($customer && ($customer->taxDetails && $customer->taxDetails->enable_billing)) {
                $job = new CreateSiigoCustomer($customer);
                dispatch($job)->delay(now()->addSeconds(10))->onQueue('redis');
            }
        }
    }
}
