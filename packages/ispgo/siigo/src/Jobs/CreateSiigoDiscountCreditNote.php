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

class CreateSiigoDiscountCreditNote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 30;

    public function __construct(
        private Invoice $invoice,
        private float $discountAmount,
        private string $description = '',
        private bool $force = false
    ) {}

    public function handle(SiigoClient $siigo)
    {
        $this->invoice->load(['customer.taxDetails', 'items']);

        $customer = $this->invoice->customer;
        if (!$customer) {
            return;
        }
        $taxDetails = $customer->taxDetails;
        if (!$this->force && (!$taxDetails || !$taxDetails->enable_billing)) {
            Log::info("Skipping Siigo Discount Credit Note creation because billing is not enabled for customer #{$customer->id}");
            return;
        }

        $info = $this->invoice->additional_information ?? [];
        if (empty($info['siigo_invoice_id'])) {
            Log::info('Skipping Siigo Discount Credit Note creation because invoice has not been synced to Siigo.', [
                'invoice_id' => $this->invoice->id
            ]);
            return;
        }

        try {
            $payload = SiigoHelper::buildDiscountCreditNotePayload($this->invoice, $this->discountAmount, $this->description);
            $response = $siigo->createCreditNote($payload);
            $body = json_decode((string) $response->getBody(), true);
            
            $id = $body['id'] ?? null;
            $name = $body['name'] ?? null;
            $number = $body['number'] ?? null;
            if ($id) {
                $discountNotes = $info['siigo_discount_credit_notes'] ?? [];
                $discountNotes[] = [
                    'id' => $id,
                    'name' => $name,
                    'number' => $number,
                    'amount' => $this->discountAmount,
                    'description' => $this->description,
                    'created_at' => now()->toIso8601String(),
                ];
                $info['siigo_discount_credit_notes'] = $discountNotes;
                $this->invoice->additional_information = $info;
                $this->invoice->save();

                try {
                    $siigo->stampCreditNote($id);
                } catch (\Exception $stampEx) {
                    Log::warning('Siigo Discount Credit Note created but stamping failed: ' . $stampEx->getMessage(), [
                        'invoice_id' => $this->invoice->id,
                        'siigo_credit_note_id' => $id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error creating Siigo Discount Credit Note: ' . $e->getMessage(), [
                'invoice_id' => $this->invoice->id,
                'discount_amount' => $this->discountAmount
            ]);
            throw $e;
        }
    }
}
