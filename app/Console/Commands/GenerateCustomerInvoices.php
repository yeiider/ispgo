<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customers\Customer;
use App\Services\Billing\CustomerBillingService;
use Carbon\Carbon;

class GenerateCustomerInvoices extends Command
{
    protected $signature = 'billing:generate-invoices {--period=}';
    protected $description = 'Genera borradores de factura por cliente';

    public function handle(CustomerBillingService $billing)
    {
        $period = $this->option('period')
            ? Carbon::createFromFormat('Y-m', $this->option('period'))
            : now();

        $this->info("Generando facturas para {$period->format('F Y')} …");

        Customer::active()->chunk(200, function ($customers) use ($billing, $period) {
            foreach ($customers as $customer) {
                $billing->generateForPeriod($customer, $period);
            }
        });

        $this->info('Listo ✔');
    }
}
