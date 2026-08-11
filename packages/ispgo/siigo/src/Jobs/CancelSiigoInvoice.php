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
        if (!empty($info['siigo_credit_note_id']) && !$this->force) {
            return;
        }

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
