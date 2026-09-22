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

class CancelSiigoInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 30;

    public function __construct(private Invoice $invoice, private bool $force = false) {}

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
            Log::info("Skipping Siigo Credit Note creation because billing (enable_billing) is not enabled for customer #{$customer->id}");
            return;
        }

        $info = $this->invoice->additional_information ?? [];
        if (empty($info['siigo_invoice_id'])) {
            // Create invoice in Siigo first so a credit note can be created against it
            $createJob = new CreateSiigoInvoice($this->invoice, $this->force);
            $createJob->handle($siigo);

            $this->invoice->refresh();
            $info = $this->invoice->additional_information ?? [];
        }

        if (empty($info['siigo_invoice_id'])) {
            Log::info('Skipping Siigo Credit Note creation because invoice has not been synced to Siigo.', [
                'invoice_id' => $this->invoice->id
            ]);
            return;
        }

        // Prevent double sync unless forced
        if ((!empty($info['siigo_credit_note_id']) || !empty($info['siigo_annulled'])) && !$this->force) {
            return;
        }

        $siigoInvoiceId = $info['siigo_invoice_id'];

        // Check if invoice in Siigo is stamped/electronic or draft
        $isStamped = false;
        try {
            $invRes = $siigo->getInvoiceByUuid($siigoInvoiceId);
            $invData = json_decode((string) $invRes->getBody(), true);
            $stampStatus = $invData['stamp']['status'] ?? null;
            $cufe = $invData['stamp']['cufe'] ?? null;
            if ($stampStatus === 'stamped' || !empty($cufe)) {
                $isStamped = true;
            }
        } catch (\Exception $getEx) {
            Log::warning("Could not fetch Siigo invoice status prior to cancellation, assuming credit note needed: " . $getEx->getMessage(), [
                'invoice_id' => $this->invoice->id,
                'siigo_invoice_id' => $siigoInvoiceId
            ]);
            // Default to credit note if status check fails
            $isStamped = true;
        }

        // 1. If invoice is draft (not stamped in DIAN), annul directly via POST /v1/invoices/{id}/annul
        if (!$isStamped) {
            try {
                $siigo->annulInvoice($siigoInvoiceId);
                $info['siigo_annulled'] = true;
                $info['siigo_annulled_at'] = now()->toIso8601String();
                $this->invoice->additional_information = $info;
                $this->invoice->save();

                Log::info("Siigo Invoice #{$siigoInvoiceId} annulled directly as draft.", [
                    'invoice_id' => $this->invoice->id
                ]);
                return;
            } catch (\Exception $annulEx) {
                Log::warning("Siigo annulInvoice failed for draft invoice #{$siigoInvoiceId}, falling back to Credit Note: " . $annulEx->getMessage(), [
                    'invoice_id' => $this->invoice->id
                ]);
                // Fallback to Credit Note if annulment fails
            }
        }

        // 2. If invoice is stamped/electronic or annulment failed, create and stamp a Credit Note
        try {
            $payload = SiigoHelper::buildCreditNotePayload($this->invoice);
            $response = $siigo->createCreditNote($payload);
            $body = json_decode((string) $response->getBody(), true);
            
            $id = $body['id'] ?? null;
            $name = $body['name'] ?? null;
            $number = $body['number'] ?? null;
            if ($id) {
                $info['siigo_credit_note_id'] = $id;
                if ($name) {
                    $info['siigo_credit_note_name'] = $name;
                }
                if ($number) {
                    $info['siigo_credit_note_number'] = $number;
                }
                $this->invoice->additional_information = $info;
                $this->invoice->save();

                // Trigger stamping if required
                try {
                    $siigo->stampCreditNote($id);
                } catch (\Exception $stampEx) {
                    Log::warning('Siigo Credit Note created but stamping failed: ' . $stampEx->getMessage(), [
                        'invoice_id' => $this->invoice->id,
                        'siigo_credit_note_id' => $id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error creating Siigo Credit Note: ' . $e->getMessage(), [
                'invoice_id' => $this->invoice->id
            ]);
            throw $e;
        }
    }
}
