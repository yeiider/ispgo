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

class DeleteSiigoInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 30;

    public function __construct(private Invoice $invoice, private bool $force = false) {}

    public function handle(SiigoClient $siigo)
    {
        $info = $this->invoice->additional_information ?? [];
        $siigoInvoiceId = $info['siigo_invoice_id'] ?? null;

        if (empty($siigoInvoiceId)) {
            Log::info("Skipping DeleteSiigoInvoice because invoice #{$this->invoice->id} has no siigo_invoice_id.");
            return;
        }

        // Check if customer has billing enabled
        $customer = $this->invoice->customer;
        if ($customer) {
            $taxDetails = $customer->taxDetails;
            if (!$this->force && (!$taxDetails || !$taxDetails->enable_billing)) {
                Log::info("Skipping Siigo Invoice deletion because billing (enable_billing) is not enabled for customer #{$customer->id}");
                return;
            }
        }

        try {
            // Check status of invoice in Siigo
            $invRes = $siigo->getInvoiceByUuid($siigoInvoiceId);
            $invData = json_decode((string) $invRes->getBody(), true);
            $stampStatus = $invData['stamp']['status'] ?? null;
            $cufe = $invData['stamp']['cufe'] ?? null;
            $isStamped = ($stampStatus === 'stamped' || !empty($cufe));

            if ($isStamped) {
                Log::warning("Invoice #{$this->invoice->id} (Siigo ID: {$siigoInvoiceId}) is stamped in DIAN and cannot be deleted via DELETE API. Issuing Credit Note instead.");
                $cancelJob = new CancelSiigoInvoice($this->invoice, $this->force);
                $cancelJob->handle($siigo);
                return;
            }

            // If draft, delete directly via DELETE /v1/invoices/{id}
            $siigo->deleteInvoice($siigoInvoiceId);
            Log::info("Successfully deleted draft Siigo invoice #{$siigoInvoiceId} for invoice #{$this->invoice->id}.");

        } catch (\Exception $e) {
            Log::error("Error deleting Siigo invoice #{$siigoInvoiceId}: " . $e->getMessage(), [
                'invoice_id' => $this->invoice->id,
                'siigo_invoice_id' => $siigoInvoiceId
            ]);
            throw $e;
        }
    }
}
