<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceUpdateStatus;
use Illuminate\Queue\InteractsWithQueue;
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
            $this->processImmediate($service, $action);
        }

    }

    private function processImmediate(\App\Models\Services\Service $service, string $action): void
    {
        $sn = $service->sn;
        if (empty($sn)) {
            Log::warning("No se pudo ejecutar acción '{$action}' porque el servicio {$service->id} no tiene SN.");
            return;
        }

        $externalId = $this->resolveExternalId($service);
        $apiManager = new ApiManager();
        try {
            if ($action === 'enable') {
                $response = $apiManager->enableOnu($sn);
                $this->triggerCatv($apiManager, $externalId, 'enable');
            } else {
                $response = $apiManager->disableOnu($sn);
                $this->triggerCatv($apiManager, $externalId, 'disable');
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

    private function triggerCatv(ApiManager $apiManager, string $externalId, string $action): void
    {
        try {
            $response = $action === 'enable'
                ? $apiManager->enableOnuCatvByExternalId($externalId)
                : $apiManager->disableOnuCatvByExternalId($externalId);

            if ($response->successful()) {
                Log::info("CATV '{$action}' ejecutado correctamente para external_id {$externalId}", [
                    'response' => $response->body(),
                ]);
            } else {
                Log::warning("Error al ejecutar CATV '{$action}' para external_id {$externalId}", [
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $exception) {
            Log::warning("Excepción al ejecutar CATV '{$action}' para external_id {$externalId}: {$exception->getMessage()}");
        }
    }

    private function resolveExternalId(\App\Models\Services\Service $service): string
    {
        // if ($service->customer && !empty($service->customer->identity_document)) {
        //     return (string)$service->customer->identity_document;
        // }

        if (!empty($service->sn)) {
            return (string)$service->sn;
        }

        return (string)$service->id;
    }
}
