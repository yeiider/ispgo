<?php

namespace App\Console\Commands;

use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use App\Models\Customers\Customer;
use App\Services\Billing\CustomerBillingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\Notify;

class GenerateCustomerInvoices extends Command
{
    protected $signature = 'billing:generate-invoices {--period=}';
    protected $description = 'Genera borradores de factura por cliente';

    /**
     * @throws \Exception
     */
    public function handle(CustomerBillingService $billing): void
    {
        $billingDate = GeneralProviderConfig::getBillingDate();
        $currentDate = Carbon::now();

        if ($currentDate->day == $billingDate) {
            $period = $this->option('period')
                ? Carbon::createFromFormat('Y-m', $this->option('period'))
                : now();

            $this->info("Generando facturas para {$period->format('F Y')} …");

            Customer::active()->chunk(200, function ($customers) use ($billing, $period) {
                foreach ($customers as $customer) {
                    try {
                        $billing->generateForPeriod($customer, $period);
                    } catch (\Exception $e) {
                        // Registrar el error en los logs
                        Log::error("Error al generar factura para el cliente {$customer->id}: {$e->getMessage()}");

                        // Enviar notificación al administrador
                        Notify::notifyError("Error al generar factura para el cliente ID: {$customer->id}. Revise los logs para más detalles.");

                        // Continuar con el siguiente cliente
                        continue;
                    }
                }
            });

            $this->info('Proceso de generación completado ✔');
        }
    }
}
