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

        // Lógica diaria: Solo actuar si el día coincide
        if ($currentDate->day == $cutOffDate) {
            $today = Carbon::now()->toDateString();

            $this->info("[EVERYDAY] Iniciando suspensión de servicios con facturas vencidas e impagas...");
            $this->info("[EVERYDAY] Fecha actual: {$today}");

            // Buscar servicios activos cuyos clientes tengan facturas vencidas y sin pagar
            // La relación es: Service -> Customer -> Invoices
            Service::where('service_status', 'active')
                ->whereHas('customer.invoices', function ($query) use ($today) {
                    $query->where('status', 'unpaid')
                        ->where('due_date', '<', $today); // Facturas que ya vencieron
                })
                ->chunk(50, function ($services) {
                    foreach ($services as $service) {
                        try {
                            $service->suspend();
                            Log::info("[EVERYDAY] Servicio ID: {$service->id} (SN: {$service->sn}) suspendido por facturas vencidas del cliente ID: {$service->customer_id}");
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
}
