<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceActive;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Jobs\RebootOnuJob;
use Ispgo\Smartolt\Jobs\ToggleCatvJob;
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
     *  t=0s   → enableOnu()       (inmediato, en este listener)
     *  t=30s  → RebootOnuJob      (reboot del equipo para que aplique cambios)
     *  t=60s  → ToggleCatvJob     (ciclo disable→enable CATV post-reboot)
     *
     * La CATV no se activa con un simple enable_catv después de habilitar la ONU;
     * el equipo físico requiere un reboot y luego un ciclo explícito de
     * disable→enable de CATV para que el servicio de TV realmente suba al cliente.
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

        Log::info("ServiceOltManagerListenerActive: ONU {$service->sn} activada. Programando reboot y toggle CATV.", [
            'service_id'  => $service->id,
            'external_id' => $externalId,
        ]);

        // ── Paso 2: Reboot de la ONU (t=30s) ───────────────────────────────
        // RebootOnuJob::dispatch($externalId, $service->id)
        //     ->onQueue('redis')
        //     ->delay(now()->addSeconds(80));

        // ── Paso 3: Toggle CATV post-reboot (t=5s) ─────────────────────────
        // Ciclo disable→enable para forzar que el equipo aplique la TV
        ToggleCatvJob::dispatch($externalId, $service->id)
            ->onQueue('redis')
            ->delay(now()->addSeconds(5));
    }

    private function resolveExternalId(\App\Models\Services\Service $service): string
    {
        if (!empty($service->sn)) {
            return (string)$service->sn;
        }

        return (string)$service->id;
    }
}
