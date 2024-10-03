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

        // Verificar que el servicio tenga un número de serie válido
        if (empty($service->sn)) {
            Log::warning("El servicio con ID {$service->id} no tiene un número de serie válido.");
            return;
        }

        // Determinar la acción (enable o disable) según el estado del servicio
        $action = $service->service_status === 'active' ? 'enable' : 'disable';

        // Agregar el SN del servicio a la lista correspondiente en caché
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

        // Obtener la lista actual de SNs para la acción
        $snList = Cache::get($cacheKey, []);

        // Agregar el SN si no está ya en la lista
        if (!in_array($sn, $snList)) {
            $snList[] = $sn;
            Cache::put($cacheKey, $snList, now()->addMinutes(10));
            Log::info("Agregado SN {$sn} al lote de acción '{$action}'.");
        }
    }
}
