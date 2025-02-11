<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use Illuminate\Console\Command;

class PopulateCustomerNameForInvoices extends Command
{
    protected $signature = 'invoices:populate-customer-name';
    protected $description = 'Poblar la columna customer_name para las facturas existentes';

    public function handle()
    {
        $this->info('Actualizando los nombres de cliente para las facturas existentes...');

        Invoice::with('customer')->chunk(100, function ($invoices) {
            foreach ($invoices as $invoice) {
                if ($invoice->customer) {
                    $invoice->customer_name = $invoice->customer->first_name . ' ' . $invoice->customer->last_name;
                    $invoice->save();
                }
            }
        });

        $this->info('Columna customer_name actualizada exitosamente.');
    }
}
