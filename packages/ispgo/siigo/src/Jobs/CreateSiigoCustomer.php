<?php

namespace Ispgo\Siigo\Jobs;

use App\Models\Customers\Customer;
use Ispgo\Siigo\Helpers\SiigoHelper;
use Ispgo\Siigo\SiigoClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSiigoCustomer implements ShouldQueue
{
    use Queueable;

    public function __construct(private Customer $customer)
    {
    }

    public function handle(SiigoClient $siigo): void
    {
        $payload = SiigoHelper::buildPayload($this->customer);
        dd($payload);
        $response = $siigo->createCustomer($payload);
        $response->getStatusCode();
    }
}
