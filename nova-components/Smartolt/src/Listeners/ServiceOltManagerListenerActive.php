<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceActive;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Jobs\ActivateCatvJob;
use Ispgo\Smartolt\Jobs\RebootOnuJob;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class ServiceOltManagerListenerActive
{
    use InteractsWithQueue;

    public $queue = 'redis';
    public $tries = 3;
    public $timeout = 120;
    public $delay = 10;

    /**
     * Secuencia de activación:
     *
     *  t=0s  → enableOnu()           (inmediato, en este listener)
     *  t=2s  → ActivateCatvJob       (delay 2s)
     *  t=5s  → RebootOnuJob          (delay 5s = 2s CATV + 3s adicionales)
     *
     * @param ServiceActive $event
     * @throws ConnectionException
     * @throws \Exception
     */
    public function handle(ServiceActive $event): void
    {
        if (!ProviderSmartOlt::getEnabled()) {
            Log::info("SmartOLT no está habilitado.");
            return;
        }

        $service = $event->service;

        if (empty($service->sn)) {
            Log::warning("El servicio con ID {$service->id} no tiene un número de serie válido.");
            return;
        }

        if ($service->service_status !== 'active') {
            return;
        }

        $externalId = $this->resolveExternalId($service);

        // ── Paso 1: Activar ONU inmediatamente ──────────────────────────────
        $apiManager = new ApiManager();
        $apiManager->enableOnu($service->sn);

        Log::info("ServiceOltManagerListenerActive: ONU {$service->sn} activada. Programando CATV y reboot.", [
            'service_id'  => $service->id,
            'external_id' => $externalId,
        ]);

        // ── Paso 2: Activar CATV tras 2 segundos ────────────────────────────
        ActivateCatvJob::dispatch($externalId, $service->id)
            ->onQueue('redis')
            ->delay(now()->addSeconds(2));

        // ── Paso 3: Reboot de la ONU 3 segundos después del CATV (t=5s) ────
        RebootOnuJob::dispatch($externalId, $service->id)
            ->onQueue('redis')
            ->delay(now()->addSeconds(5));
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
