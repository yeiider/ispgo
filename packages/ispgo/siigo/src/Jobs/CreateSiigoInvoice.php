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

class CreateSiigoInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Invoice $invoice) {}

    public function handle(SiigoClient $siigo)
    {
        // Reload relations if needed
        $this->invoice->load(['customer.taxDetails', 'items']);

        // Check if customer has billing enabled
        $customer = $this->invoice->customer;
        if (!$customer) {
            return;
        }
        $taxDetails = $customer->taxDetails;
        if (!$taxDetails || !$taxDetails->enable_billing) {
            Log::info("Skipping Siigo Invoice creation because billing (enable_billing) is not enabled for customer #{$customer->id}");
            return;
        }

        // Prevent double sync
        $info = $this->invoice->additional_information ?? [];
        if (!empty($info['siigo_invoice_id'])) {
            return;
        }

        try {
            $payload = SiigoHelper::buildInvoicePayload($this->invoice);
            $response = $siigo->createInvoice($payload);
            $body = json_decode((string) $response->getBody(), true);
            
            $id = $body['id'] ?? null;
            if ($id) {
                // Save Siigo info
                $info['siigo_invoice_id'] = $id;
                $info['siigo_prefix'] = $body['prefix'] ?? 'FV';
                $info['siigo_consecutive'] = $body['number'] ?? null;
                $info['siigo_date'] = $body['date'] ?? null;
                $this->invoice->additional_information = $info;
                $this->invoice->save();

                // Stamp it
                try {
                    $siigo->stampInvoice($id);
                } catch (\Exception $stampEx) {
                    Log::warning('Siigo Invoice created but stamping failed: ' . $stampEx->getMessage(), [
                        'invoice_id' => $this->invoice->id,
                        'siigo_invoice_id' => $id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error creating Siigo Invoice: ' . $e->getMessage(), [
                'invoice_id' => $this->invoice->id
            ]);
            throw $e;
        }
    }
}
