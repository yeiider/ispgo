<?php

namespace Ispgo\Siigo\Jobs;

use App\Models\Customers\Customer;
use Ispgo\Siigo\Helpers\SiigoHelper;
use Ispgo\Siigo\SiigoClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateSiigoCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;

    private bool $force = false;

    public function __construct(private Customer $customer, bool $force = false)
    {
        $this->force = $force;
    }

    public function handle(SiigoClient $siigo): void
    {
        $this->customer->load(['taxDetails', 'addresses']);
        $taxDetails = $this->customer->taxDetails;

        if (!$this->force) {
            $trigger = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getSyncCustomersTrigger();
            if ($trigger !== 'all') {
                if (!$taxDetails || !$taxDetails->enable_billing) {
                    Log::info("Skipping Siigo Customer sync because billing (enable_billing) is not enabled for customer #{$this->customer->id}");
                    return;
                }
            }
        }

        $identification = SiigoHelper::getCustomerIdentification($this->customer);
        if (empty($identification)) {
            Log::error("Skipping Siigo Customer sync: identification is empty for customer #{$this->customer->id}");
            return;
        }

        $payload = SiigoHelper::buildPayload($this->customer);

        // Search if customer already exists in Siigo to determine whether to create or update
        $existingCustomer = null;
        try {
            Log::info("Searching for customer #{$this->customer->id} (identification: {$identification}) in Siigo...");
            $searchResponse = $siigo->getCustomer($identification);
            $searchBody = json_decode((string) $searchResponse->getBody(), true);
            $results = $searchBody['results'] ?? [];
            if (!empty($results)) {
                $existingCustomer = $results[0];
                Log::info("Customer already exists in Siigo with UUID: {$existingCustomer['id']}");
            }
        } catch (\Exception $searchEx) {
            Log::error("Failed to search for customer #{$this->customer->id} in Siigo: " . $searchEx->getMessage());
            throw $searchEx;
        }

        try {
            if ($existingCustomer && !empty($existingCustomer['id'])) {
                Log::info("Updating customer #{$this->customer->id} in Siigo...");
                $siigo->updateCustomer($existingCustomer['id'], $payload);
                Log::info("Customer #{$this->customer->id} successfully updated in Siigo.");
            } else {
                Log::info("Creating customer #{$this->customer->id} in Siigo...");
                $siigo->createCustomer($payload);
                Log::info("Customer #{$this->customer->id} successfully created in Siigo.");
            }
        } catch (\Exception $e) {
            Log::error("Error syncing customer #{$this->customer->id} to Siigo: " . $e->getMessage(), [
                'payload' => $payload,
                'customer_id' => $this->customer->id,
            ]);
            throw $e;
        }
    }
}
