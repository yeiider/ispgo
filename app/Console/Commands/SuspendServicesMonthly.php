<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SuspendServicesMonthly extends Command
{
    protected $signature = 'services:suspend_everyday'; // Cambiar el signature
    protected $description = 'Suspend services everyday if it matches the cut-off day'; // Actualizar descripción

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $cutOffDate = GeneralProviderConfig::getCutOffDate(); // Día configurado
        $currentDate = Carbon::now();

        if ($currentDate->day != $cutOffDate) {
            return;
        }

        $today = Carbon::now()->startOfDay();

        $this->info("[EVERYDAY] Iniciando suspensión de servicios con facturas vencidas e impagas sin promesa vigente...");
        $this->info("[EVERYDAY] Fecha actual: {$today->toDateString()}");

        Service::where('service_status', 'active')
            ->whereHas('customer.invoices', function ($query) use ($today) {
                $query->where('status', 'unpaid')
                    ->where('outstanding_balance', '>', 0)
                    ->whereDate('due_date', '<', $today)
                    ->whereDoesntHave('paymentPromises', function ($promiseQuery) use ($today) {
                        $promiseQuery->where('status', 'pending')
                            ->whereDate('promise_date', '>=', $today);
                    });
            })
            ->chunk(50, function ($services) {
                foreach ($services as $service) {
                    try {
                        $service->suspend();
                        Log::info("[EVERYDAY] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido por facturas vencidas sin promesa vigente del cliente ID: {$service->customer_id}");
                        $this->info("[EVERYDAY] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido.");
                    } catch (\Exception $e) {
                        Log::error("[EVERYDAY] Error al suspender servicio ID: {$service->id} - {$e->getMessage()}");
                        $this->error("[EVERYDAY] Error al suspender servicio ID: {$service->id}");
                    }
                }
            });

        $this->info("[EVERYDAY] Proceso de suspensión completado.");
    }
}
