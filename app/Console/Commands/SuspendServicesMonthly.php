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
            $this->info("[EVERYDAY] Iniciando suspensión de servicios con facturas impagas...");

            Service::where('service_status', '!=', 'free')
                ->whereHas('invoices', function ($query) {
                    $query->where('status', 'unpaid');
                })
                ->chunk(50, function ($services) {
                    foreach ($services as $service) {
                        try {
                            $service->suspend();
                            Log::info("[EVERYDAY] Servicio ID: {$service->id} suspendido.");
                            $this->info("[EVERYDAY] Servicio ID: {$service->id} suspendido.");
                        } catch (\Exception $e) {
                            Log::error("[EVERYDAY] Error al suspender servicio ID: {$service->id} - {$e->getMessage()}");
                            $this->error("[EVERYDAY] Error al suspender servicio ID: {$service->id}");
                        }
                    }
                });

            $this->info("[EVERYDAY] Proceso de suspensión completado.");
        } else {
            $this->info("[EVERYDAY] Hoy no es el día de corte configurado ({$cutOffDate}). No se realizó ninguna acción.");
        }
    }
}
