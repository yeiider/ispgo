<?php

namespace App\Jobs;

use App\Events\FinalizeInvoice;
use App\Models\Services\Service;
use App\Models\User;
use App\Services\Billing\CustomerBillingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GenerateServiceInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $serviceId;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param mixed $serviceId
     * @param mixed|null $userId
     */
    public function __construct($serviceId, $userId = null)
    {
        $this->serviceId = $serviceId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @param CustomerBillingService $billingService
     * @return void
     */
    public function handle(CustomerBillingService $billingService)
    {
        Log::info("Starting GenerateServiceInvoiceJob for Service ID: {$this->serviceId}");

        try {
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    Auth::setUser($user);
                }
            }

            $service = Service::withoutGlobalScope('router_filter')
                ->with('customer')
                ->find($this->serviceId);

            if (!$service) {
                Log::warning("GenerateServiceInvoiceJob: Service not found for ID: {$this->serviceId}");
                return;
            }

            $customer = $service->customer;
            if (!$customer) {
                Log::warning("GenerateServiceInvoiceJob: Customer not found for Service ID: {$this->serviceId}");
                return;
            }

            if (in_array($service->service_status, ['free', 'inactive', 'pending'])) {
                Log::info("GenerateServiceInvoiceJob: Service ID {$this->serviceId} has status '{$service->service_status}'. Skipping invoice generation.");
                return;
            }

            $invoice = $billingService->generateForPeriod($customer, now(), (int) $service->id);

            if ($invoice) {
                if (empty($invoice->service_id)) {
                    $invoice->service()->associate($service);
                }
                if ($service->router_id && empty($invoice->router_id)) {
                    $invoice->router_id = $service->router_id;
                }
                $invoice->save();

                event(new FinalizeInvoice($invoice));
                Log::info("GenerateServiceInvoiceJob: Invoice {$invoice->id} successfully generated for Service ID: {$this->serviceId}");
            } else {
                Log::warning("GenerateServiceInvoiceJob: No invoice generated for Service ID: {$this->serviceId} (customer ID {$customer->id})");
            }
        } catch (\Exception $e) {
            Log::error("Error in GenerateServiceInvoiceJob for Service ID {$this->serviceId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            $this->fail($e);
        }
    }
}
