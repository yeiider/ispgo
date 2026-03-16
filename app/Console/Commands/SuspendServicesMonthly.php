<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SuspendServicesMonthly extends Command
{
    protected $signature = 'services:suspend_everyday {router_id?}'; // Cambiar el signature
    protected $description = 'Suspend services everyday if it matches the cut-off day'; // Actualizar descripción

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDate = Carbon::now();
        $today = Carbon::now()->startOfDay();

        $routerId = $this->argument('router_id');

        $this->info("[EVERYDAY] Iniciando suspensión de servicios con facturas vencidas e impagas sin promesa vigente...");
        $this->info("[EVERYDAY] Fecha actual: {$today->toDateString()}");
        if ($routerId) {
            $this->info("[EVERYDAY] Filtrando por Router ID: {$routerId}");
        }

        $routers = \App\Models\Router::query();
        if ($routerId) {
            $routers->where('id', $routerId);
        }

        // Recorrer los routers
        $routers->get()->each(function ($router) use ($currentDate, $today) {
            // Obtener la fecha de corte configurada para este router
            $cutOffDate = GeneralProviderConfig::getCutOffDate($router->id);

            // Solo procesar si hoy es el día de corte de este router
            if ($currentDate->day != $cutOffDate) {
                return;
            }

            $this->info("[EVERYDAY] Procesando router {$router->name} (ID: {$router->id}) - Día de corte: {$cutOffDate}");

            // Suspender servicios activos de clientes asignados a este router
            Service::where('service_status', 'active')
                ->whereHas('customer', function ($query) use ($router) {
                    $query->where('router_id', $router->id);
                })
                ->whereHas('customer.invoices', function ($query) use ($today) {
                    $query->where('status', 'unpaid')
                        ->where('outstanding_balance', '>', 0)
                        ->whereDate('due_date', '<', $today)
                        ->whereDoesntHave('paymentPromises', function ($promiseQuery) use ($today) {
                            $promiseQuery->where('status', 'pending')
                                ->whereDate('promise_date', '>=', $today);
                        });
                })
                ->chunk(50, function ($services) use ($router) {
                    foreach ($services as $service) {
                        try {
                            $service->suspend();
                            Log::info("[EVERYDAY] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido por facturas vencidas sin promesa vigente del cliente ID: {$service->customer_id} del router {$router->id}");
                            $this->info("[EVERYDAY] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido.");
                        } catch (\Exception $e) {
                            Log::error("[EVERYDAY] Error al suspender servicio ID: {$service->id} del router {$router->id} - {$e->getMessage()}");
                            $this->error("[EVERYDAY] Error al suspender servicio ID: {$service->id}");
                        }
                    }
                });
        });

        $this->info("[EVERYDAY] Proceso de suspensión completado.");
    }
}
