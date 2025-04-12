<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceUpdateStatus;
use App\Models\SmartOltBatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class ServiceOltManagerListener
{

    use InteractsWithQueue;

    public $queue = 'redis';
    public $tries = 3;
    public $timeout = 120;
    public $delay = 10;

    /**
     * Handle the event.
     *
     * @param ServiceUpdateStatus $event
     * @return void
     */
    public function handle(ServiceUpdateStatus $event)
    {

        if (!ProviderSmartOlt::getEnabled()) {
            Log::info("SmartOLT no está habilitado.");
            return;
        }


        $service = $event->service;

        // Verificar que el servicio tenga un número de serie válido
        if (empty($service->sn)) {
            Log::warning("El servicio con ID {$service->id} no tiene un número de serie válido.");
            return;
        }

        // Determinar la acción (enable o disable) según el estado del servicio
        $action = $service->service_status === 'active' ? 'enable' : 'disable';

        Log::info("Se ha guardado la information de sn {$service->sn}");
        // Agregar el SN del servicio a la lista correspondiente en caché
        if ($service->service_status === "active" || $service->service_status === "suspended") {
            $this->processImmediate($service->sn, $action);
        }

    }

    private function processImmediate(string $sn, string $action): void
    {
        $apiManager = new ApiManager();
        try {
            if ($action === 'enable') {
                $response = $apiManager->enableOnu($sn);
            } else {
                $response = $apiManager->disableOnu($sn);
            }

            if ($response->successful()) {
                Log::info("Acción '{$action}' ejecutada correctamente para {$sn}", [
                    'response' => $response->body(),
                ]);
            } else {
                Log::error("Error ejecutando acción '{$action}' para {$sn}", [
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Excepción al ejecutar acción '{$action}' para {$sn}: {$e->getMessage()}");
        }
    }
}
