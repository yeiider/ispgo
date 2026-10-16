<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceSuspend;
use App\Events\ServiceUpdateStatus;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class ServiceOltManagerListenerSuspend
{
    use InteractsWithQueue;

    public $queue = 'redis';
    public $tries = 3;
    public $timeout = 120;
    public $delay = 10;
    /**
     * Handle the event.
     *
     * @param ServiceSuspend $event
     * @return void
     * @throws ConnectionException
     * @throws \Exception
     */
    public function handle(ServiceSuspend $event)
    {

        $service = $event->service;
        $routerId = \App\Support\RouterScope::fromService($service);

        // Cada zona decide si usa SmartOLT; sin configuración propia usa la de por defecto.
        if (!\App\Support\SmartOltGate::enabled($routerId)) {
            Log::info("SmartOLT no está habilitado para la zona del servicio {$service->id}.");
            return;
        }
        if (empty($service->sn)) {
            Log::warning("El servicio con ID {$service->id} no tiene un número de serie válido.");
            return;
        }
        $apiManager = new ApiManager(\App\Support\RouterScope::fromService($service));
        if ($service->service_status === 'suspended'){
            $apiManager->disableOnu($service->sn);
            $externalId = $this->resolveExternalId($service);
            $this->triggerCatv($apiManager, $externalId, 'disable');
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
