<?php

namespace Ispgo\Mikrotik\Listener;

use App\Events\ServiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Ispgo\Mikrotik\Services\SimpleQueueManager;
use Ispgo\Mikrotik\Services\PPPoEManager;
use Ispgo\Mikrotik\Services\PlanFormatter;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Illuminate\Support\Facades\Log;

class ServiceCreateListener implements ShouldQueue
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
     * @param ServiceCreated $event
     * @return void
     */
    public function handle(ServiceCreated $event): void
    {
        // Verificar si Mikrotik está habilitado en la configuración general
        $service = $event->service;
        if ($service->service_status !== "active")
            return;

        if (!MikrotikConfigProvider::getEnabled()) {
            Log::warning('Mikrotik no está habilitado. No se puede proceder con la creación del servicio.');
            return;
        }
        Log::info('Iniciando el listener ServiceCreateListener.');

        // Obtener el servicio y su plan desde el evento
        $plan = $service->plan;
        // Instanciar el PlanFormatter, SimpleQueueManager, y PPPoEManager
        $planFormatter = new PlanFormatter();
        $simpleQueueManager = new SimpleQueueManager();
        $pppoeManager = new PPPoEManager();

        // Formatear los datos del plan y el servicio
        $formattedData = $planFormatter->formatPlanAndService($plan, $service);

        try {
            // Crear Simple Queue si está habilitado
            if (MikrotikConfigProvider::getSimpleQueueEnabled()) {
                Log::info('Antes de llamar a createSimpleQueueFromFormattedData.');

                $simpleQueueManager->createSimpleQueueFromFormattedData($formattedData['service_ip'], $formattedData);
                Log::info('Simple Queue creada para el servicio: ' . $formattedData['service_name']);
            }

            // Crear PPPoE si está habilitado
            if (MikrotikConfigProvider::getPppEnabled()) {
                if (!$plan->is_synchronized) {
                    Log::warning('Este plan no se ha creado dentro de los PPP profile');
                    return;
                }
                // Verificar si el pool de IP está activo
                $useIpPool = MikrotikConfigProvider::getIpPoolEnabled(); // Obtener si el pool de IP está activo
                $service = MikrotikConfigProvider::getServiceType();
                $password = MikrotikConfigProvider::getPasswordPPPSecret();
                // Si el pool de IP está activo, no enviar dirección IP
                $serviceIp = $useIpPool ? null : $formattedData['service_ip'];
                $profile = strtolower(str_replace(' ', '_', $formattedData['plan_name']));
                // Crear PPPoE Client utilizando el nombre del plan como perfil
                $pppoeManager->createPPPoEClient(
                    $formattedData['service_name'],     // Usar el nombre del plan como nombre de usuario
                    $password,              // Contraseña predeterminada, cambiar según corresponda
                    $service,                         // Tipo de servicio
                    $profile,     // Usar el nombre del plan como perfil
                    $serviceIp                       // Dirección IP (null si se usa pool de IPs)
                );
                Log::info('PPPoE creado para el servicio: ' . $formattedData['service_name']);
            }

        } catch (\Exception $e) {
            // Si falla la creación, registrar el error en los logs
            Log::error('Error al crear la configuración para el servicio: ' . $formattedData['service_name'] . '. ' . $e->getMessage());
        }
    }
}
