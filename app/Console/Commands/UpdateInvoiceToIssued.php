<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateInvoiceToIssued extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-invoice-to-issued';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update building invoices to issued for the current billing period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentBillingPeriod = now()->format('Y-m'); // Obtener el periodo actual

        $invoices = Invoice::where('billing_period', $currentBillingPeriod) // Filtrar facturas del periodo actual
        ->where('state', 'building') // Filtrar facturas con el estado "building"
        ->get();

        foreach ($invoices as $invoice) {
            try {
                $invoice->finalize();
                $this->info("Invoice ID {$invoice->id} updated to issued."); // Mensaje informativo
            } catch (\Exception $e) {
                Log::error("Error updating invoice ID {$invoice->id}: " . $e->getMessage()); // Registrar error
                $this->error("Error updating invoice ID {$invoice->id}. Continuing with the next."); // Mensaje de error
            }
        }

        $this->info('Process completed'); // Mensaje de finalización
    }
}
