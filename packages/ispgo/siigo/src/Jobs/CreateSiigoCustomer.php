<?php

namespace Ispgo\Siigo\Jobs;

use App\Models\Customers\Customer;
use Ispgo\Siigo\Helpers\SiigoHelper;
use Ispgo\Siigo\SiigoClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log;

class CreateSiigoCustomer implements ShouldQueue
{
    use Queueable;

    public function __construct(private Customer $customer)
    {
    }

    public function handle(SiigoClient $siigo): void
    {
        $this->customer->load('taxDetails');
        $taxDetails = $this->customer->taxDetails;
        if (!$taxDetails || !$taxDetails->enable_billing) {
            Log::info("Skipping Siigo Customer creation because billing (enable_billing) is not enabled for customer #{$this->customer->id}");
            return;
        }

        $payload = SiigoHelper::buildPayload($this->customer);
        $siigo->createCustomer($payload);
    }
}
