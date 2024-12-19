<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Log;

class SuspendServicesMonthly extends Command
{
    protected $signature = 'services:suspend_monthly';
    protected $description = 'Suspend services monthly based on cut-off date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (GeneralProviderConfig::getAutomaticCutOff()) {
            $cutOffDate = GeneralProviderConfig::getCutOffDate();
            $currentDate = Carbon::now();

            if ($currentDate->day == $cutOffDate) {
                $this->info("Iniciando suspensión de servicios con facturas impagas...");

                // Procesar servicios en lotes de 50
                Service::where('service_status', '!=', 'free')
                    ->whereHas('invoices', function ($query) {
                        $query->where('status', 'unpaid');
                    })
                    ->chunk(50, function ($services) {
                        foreach ($services as $service) {
                            try {
                                $service->suspend();
                                $this->info("Servicio ID: {$service->id} suspendido.");
                            } catch (\Exception $e) {
                                $this->error("Error al suspender servicio ID: {$service->id} - {$e->getMessage()}");
                                $this->error("Error al suspender servicio ID: {$service->id}");
                            }
                        }
                    });

                $this->info("Proceso de suspensión completado.");
            } else {
                $this->info("Hoy no es el día de corte configurado ({$cutOffDate}). No se realizó ninguna acción.");
            }
        } else {
            $this->info("La suspensión automática de servicios está deshabilitada.");
        }
    }
}
