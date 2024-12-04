<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceUpdateStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class ServiceOltManagerListener implements ShouldQueue
{
    use InteractsWithQueue;

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

        if (empty($service->sn)) {
            Log::warning("El servicio con ID {$service->id} no tiene un número de serie válido.");
            return;
        }

        $action = $service->service_status === 'active' ? 'enable' : 'disable';

        $this->addToBatch($service->sn, $action);
    }

    /**
     * Agrega el número de serie a la lista de acciones en caché.
     *
     * @param string $sn
     * @param string $action
     * @return void
     */
    private function addToBatch(string $sn, string $action): void
    {
        $cacheKey = "smartolt_batch_{$action}";

        $snList = Cache::get($cacheKey, []);

        if (!in_array($sn, $snList)) {
            $snList[] = $sn;
            Cache::put($cacheKey, $snList, now()->addMinutes(10));
            Log::info("Agregado SN {$sn} al lote de acción '{$action}'.");
        }
    }
}
