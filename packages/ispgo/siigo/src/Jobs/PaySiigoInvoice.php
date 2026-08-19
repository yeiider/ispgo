<?php
namespace Ispgo\Siigo\Jobs;

use App\Models\Invoice\Invoice;
use Ispgo\Siigo\Helpers\SiigoHelper;
use Ispgo\Siigo\SiigoClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaySiigoInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 30;

    public function __construct(private Invoice $invoice, private float $amount, private bool $force = false) {}

    public function handle(SiigoClient $siigo)
    {
        $this->invoice->load(['customer.taxDetails', 'items']);

        // Check if customer has billing enabled
        $customer = $this->invoice->customer;
        if (!$customer) {
            return;
        }
        $taxDetails = $customer->taxDetails;
        if (!$this->force && (!$taxDetails || !$taxDetails->enable_billing)) {
            Log::info("Skipping Siigo Voucher creation because billing (enable_billing) is not enabled for customer #{$customer->id}");
            return;
        }

        // Check if invoice has been synced to Siigo, if not sync it first!
        $info = $this->invoice->additional_information ?? [];
        if (empty($info['siigo_invoice_id'])) {
            $createJob = new CreateSiigoInvoice($this->invoice, $this->force);
            $createJob->handle($siigo);
            
            $this->invoice->refresh();
            $info = $this->invoice->additional_information ?? [];
            // Sleep 2 seconds to give Siigo API time to commit/index the invoice into cartera
            sleep(2);
        }

        if (empty($info['siigo_invoice_id'])) {
            Log::error('Cannot create Siigo Voucher because Siigo Invoice could not be created/found.', [
                'invoice_id' => $this->invoice->id
            ]);
            return;
        }

        // Prevent double sync of the voucher unless forced
        if (!empty($info['siigo_voucher_id']) && !$this->force) {
            return;
        }

        try {
            $payload = SiigoHelper::buildVoucherPayload($this->invoice, $this->amount);
            $response = $siigo->createVoucher($payload);
            $body = json_decode((string) $response->getBody(), true);
            
            $id = $body['id'] ?? null;
            $name = $body['name'] ?? null;
            $number = $body['number'] ?? null;
            if ($id) {
                $info['siigo_voucher_id'] = $id;
                if ($name) {
                    $info['siigo_voucher_name'] = $name;
                }
                if ($number) {
                    $info['siigo_voucher_number'] = $number;
                }
                $this->invoice->additional_information = $info;
                $this->invoice->save();
            }
        } catch (\Exception $e) {
            Log::error('Error creating Siigo Voucher/Payment: ' . $e->getMessage(), [
                'invoice_id' => $this->invoice->id,
                'amount' => $this->amount
            ]);
            throw $e;
        }
    }
}
