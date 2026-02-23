<?php

namespace App\Console\Commands;

use App\Events\FinalizeInvoiceBySchedule;
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
        $currentDate = Carbon::now();
        $period = $this->option('period')
            ? Carbon::createFromFormat('Y-m', $this->option('period'))
            : now();

        $this->info("Generando facturas para {$period->format('F Y')} …");

        // Recorrer todos los routers
        \App\Models\Router::all()->each(function ($router) use ($billing, $period, $currentDate) {
            // Obtener la fecha de facturación configurada para este router
            $billingDate = GeneralProviderConfig::getBillingDate($router->id);

            // Solo procesar si hoy es el día de facturación de este router
            if ($currentDate->day == $billingDate) {
                $this->info("Procesando router {$router->name} (ID: {$router->id}) - Día de facturación: {$billingDate}");

                // Obtener los clientes activos de este router
                Customer::active()
                    ->where('router_id', $router->id)
                    ->chunk(200, function ($customers) use ($billing, $period, $router) {
                        foreach ($customers as $customer) {
                            try {
                                $billing->generateForPeriod($customer, $period);

                            } catch (\Exception $e) {
                                // Registrar el error en los logs
                                Log::error("Error al generar factura para el cliente {$customer->id} del router {$router->id}: {$e->getMessage()}");

                                // Enviar notificación al administrador
                                try {
                                    Notify::notifyError("Error cliente ID: {$customer->id} del router {$router->name}. Ver logs.");
                                } catch (\Throwable $notifyError) {
                                    Log::error("Fallo al enviar notificación de error: " . $notifyError->getMessage());
                                }

                                // Continuar con el siguiente cliente
                                continue;
                            }
                        }
                    });

                event(new FinalizeInvoiceBySchedule());
            }
        });

        $this->info('Proceso de generación completado ✔');
    }
}
