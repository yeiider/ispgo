<?php

namespace Ispgo\Smartolt\Listeners;

use App\Events\ServiceUpdateStatus;
use App\Models\SmartOltBatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
        if ($service->service_status === "active" || $service->service_status === "suspended"){
            $this->addToBatch($service->sn, $action);
        }

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
        $existing = SmartOltBatch::where('action', $action)->orderByDesc('id')->first();

        if (!$existing || count($existing->sn_list) >= 10) {
            $existing = SmartOltBatch::create([
                'action' => $action,
                'sn_list' => [$sn],
            ]);
        } else {
            $list = $existing->sn_list;
            if (!in_array($sn, $list)) {
                $list[] = $sn;
                $existing->update(['sn_list' => $list]);
            }
        }

        Log::info("Agregado SN {$sn} a batch DB con acción '{$action}'");
    }
}
