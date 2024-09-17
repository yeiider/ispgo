<?php

namespace Ispgo\Mikrotik\Listener;

use App\Events\ServiceUpdateStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\Services\PPPoEManager;
use Ispgo\Mikrotik\Services\SimpleQueueManager;
use Illuminate\Support\Facades\Log;

class ServiceChangeStatus implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'redis';

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The number of seconds to delay the job.
     *
     * @var int
     */
    public $delay = 10;

    /**
     * Handle the event.
     *
     * @param ServiceUpdateStatus $event
     * @return void
     * @throws \Exception
     */
    public function handle(ServiceUpdateStatus $event)
    {
        // Verificar si PPPoE está habilitado en la configuración
        if (MikrotikConfigProvider::getEnabled()) {
            $service = $event->service;

            // Instanciar los manejadores de PPPoE y SimpleQueue
            $pppoeManager = new PPPoEManager();
            $simpleQueueManager = new SimpleQueueManager();

            if ($service->service_status === "suspended") {
                Log::info("El servicio está suspendido. Deshabilitando el servicio...");

                // Deshabilitar PPPoE si está habilitado
                if (MikrotikConfigProvider::getPppEnabled()) {
                    $pppoeManager->disablePPPoEClient($service->service_name);
                    Log::info("Servicio PPPoE deshabilitado para: " . $service->service_name);
                }

                // Deshabilitar Simple Queue si está habilitado
                if (MikrotikConfigProvider::getSimpleQueueEnabled()) {
                    $simpleQueueManager->disableSimpleQueueClient($service->service_name);
                    Log::info("Simple Queue deshabilitada para la IP: " . $service->service_name);
                }
            } elseif ($service->service_status === "active") {
                Log::info("El servicio está activo. Habilitando el servicio...");

                // Habilitar PPPoE si está habilitado
                if (MikrotikConfigProvider::getPppEnabled()) {
                    $pppoeManager->enablePPPoEClient($service->service_name);
                    Log::info("Servicio PPPoE habilitado para: " . $service->service_name);
                }

                // Habilitar Simple Queue si está habilitado
                if (MikrotikConfigProvider::getSimpleQueueEnabled()) {
                    $simpleQueueManager->enableSimpleQueueEClient($service->service_ip);
                    Log::info("Simple Queue habilitada para la IP: " . $service->service_ip);
                }
            }
        }
    }
}
