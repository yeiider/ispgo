<?php

namespace App\Console\Commands;

use App\Models\Router;
use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SuspendServicesMonthly extends Command
{
    protected $signature = 'services:suspend_everyday {router_id?}';
    protected $description = 'Suspend services everyday if it matches the cut-off day (supports per-service billing mode)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDate = Carbon::now();
        $today       = Carbon::now()->startOfDay();
        $routerId    = $this->argument('router_id');

        $this->info("[EVERYDAY] Iniciando suspensión de servicios con facturas vencidas e impagas sin promesa vigente...");
        $this->info("[EVERYDAY] Fecha actual: {$today->toDateString()}");

        if ($routerId) {
            $this->info("[EVERYDAY] Filtrando por Router ID: {$routerId}");
        }

        $routersQuery = Router::query();
        if ($routerId) {
            $routersQuery->where('id', $routerId);
        }

        $routersQuery->get()->each(function ($router) use ($currentDate, $today) {
            $cutOffDate = GeneralProviderConfig::getCutOffDate($router->id);

            if ($currentDate->day != $cutOffDate) {
                return;
            }

            $this->info("[EVERYDAY] Procesando router {$router->name} (ID: {$router->id}) - Día de corte: {$cutOffDate}");

            // Obtener servicios activos del router con su customer cargado
            $services = Service::withoutGlobalScope('router_filter')
                ->where('service_status', 'active')
                ->where('router_id', $router->id)
                ->with('customer')
                ->get();

            foreach ($services as $service) {
                try {
                    $customer = $service->customer;

                    if (!$customer) {
                        continue;
                    }

                    if ($customer->usesPerServiceBilling()) {
                        // ── Modo per_service: verificar facturas propias del servicio ──
                        $this->suspendIfServiceHasUnpaidInvoices($service, $customer, $today, $router);
                    } else {
                        // ── Modo total (default): verificar facturas del cliente completo ──
                        $this->suspendIfCustomerHasUnpaidInvoices($service, $customer, $today, $router);
                    }
                } catch (\Exception $e) {
                    Log::error("[EVERYDAY] Error al procesar servicio ID: {$service->id} del router {$router->id} - {$e->getMessage()}");
                    $this->error("[EVERYDAY] Error al procesar servicio ID: {$service->id}");
                }
            }
        });

        $this->info("[EVERYDAY] Proceso de suspensión completado.");
    }

    /**
     * Modo total (default).
     * Suspende el servicio si el CLIENTE tiene alguna factura general (sin service_id
     * obligatorio) vencida, unpaid y sin promesa de pago vigente.
     */
    protected function suspendIfCustomerHasUnpaidInvoices(Service $service, $customer, Carbon $today, $router): void
    {
        $hasUnpaid = $customer->invoices()
            ->where('status', 'unpaid')
            ->where('outstanding_balance', '>', 0)
            ->whereDate('due_date', '<', $today)
            ->whereDoesntHave('paymentPromises', function ($q) use ($today) {
                $q->where('status', 'pending')
                  ->whereDate('promise_date', '>=', $today);
            })
            ->exists();

        if ($hasUnpaid) {
            $service->suspend();
            Log::info("[EVERYDAY] [MODO TOTAL] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido - Cliente ID: {$customer->id} - Router: {$router->id}");
            $this->info("[EVERYDAY] [MODO TOTAL] Servicio ID: {$service->id} suspendido (cliente {$customer->id} con facturas vencidas).");
        }
    }

    /**
     * Modo per_service.
     * Suspende el servicio ÚNICAMENTE si ese servicio tiene su propia factura
     * (con service_id vinculado) vencida, unpaid y sin promesa de pago vigente.
     * Si el servicio no tiene factura vencida, aunque otros servicios del cliente
     * sí la tengan, este servicio NO se suspende.
     */
    protected function suspendIfServiceHasUnpaidInvoices(Service $service, $customer, Carbon $today, $router): void
    {
        $hasUnpaid = $service->invoices()
            ->where('status', 'unpaid')
            ->where('outstanding_balance', '>', 0)
            ->whereDate('due_date', '<', $today)
            ->whereDoesntHave('paymentPromises', function ($q) use ($today) {
                $q->where('status', 'pending')
                  ->whereDate('promise_date', '>=', $today);
            })
            ->exists();

        if ($hasUnpaid) {
            $service->suspend();
            Log::info("[EVERYDAY] [MODO PER-SERVICE] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido - Cliente ID: {$customer->id} - Router: {$router->id}");
            $this->info("[EVERYDAY] [MODO PER-SERVICE] Servicio ID: {$service->id} suspendido (factura vencida propia del servicio).");
        }
    }
}
