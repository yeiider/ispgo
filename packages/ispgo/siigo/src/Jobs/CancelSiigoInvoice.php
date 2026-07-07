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

    public function __construct(private Invoice $invoice) {}

    public function handle(SiigoClient $siigo)
    {
        $this->invoice->load(['customer.taxDetails', 'items']);

        $info = $this->invoice->additional_information ?? [];
        if (empty($info['siigo_invoice_id'])) {
            Log::info('Skipping Siigo Credit Note creation because invoice has not been synced to Siigo.', [
                'invoice_id' => $this->invoice->id
            ]);
            return;
        }

        // Prevent double sync
        if (!empty($info['siigo_credit_note_id'])) {
            return;
        }

        try {
            $payload = SiigoHelper::buildCreditNotePayload($this->invoice);
            $response = $siigo->createCreditNote($payload);
            $body = json_decode((string) $response->getBody(), true);
            
            $id = $body['id'] ?? null;
            if ($id) {
                $info['siigo_credit_note_id'] = $id;
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
