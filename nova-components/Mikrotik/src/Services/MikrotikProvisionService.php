<?php

namespace Ispgo\Mikrotik\Services;

use App\Models\Services\Service;
use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\Exceptions\MikrotikApiException;

/**
 * Servicio de Provisión de Mikrotik
 * 
 * Orquesta el flujo de:
 * 1. Obtener DHCP leases
 * 2. Seleccionar y amarrar IP a MAC
 * 3. Crear Simple Queue con velocidades del plan
 * 4. Actualizar el servicio con la IP y MAC
 */
class MikrotikProvisionService
{
    private MikrotikApiClient $apiClient;
    private int $routerId;

    public function __construct(int $routerId)
    {
        $this->routerId = $routerId;
        $this->apiClient = new MikrotikApiClient($routerId);
    }

    /**
     * Obtener todos los DHCP leases disponibles
     * 
     * @return array Lista de leases con información de IP, MAC, estado, etc.
     */
    public function getDhcpLeases(): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado para este router');
        }

        $response = $this->apiClient->getDhcpLeases();
        
        return $response['leases'] ?? [];
    }

    /**
     * Amarrar una IP a un servicio
     * 
     * @param Service $service El servicio a provisionar
     * @param string $selectedIp La IP seleccionada del DHCP lease
     * @param string $macAddress La MAC address del dispositivo
     * @return array Resultado de la operación
     */
    public function bindIpToService(Service $service, string $selectedIp, string $macAddress): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado para este router');
        }

        if (!MikrotikConfigProvider::isDhcpEnabled($this->routerId)) {
            throw new MikrotikApiException('El binding DHCP no está habilitado para este router');
        }

        $comment = $this->generateComment($service);

        try {
            $result = $this->apiClient->bindDhcpLease($macAddress, $selectedIp, $comment);

            // Actualizar el servicio con la nueva IP y MAC
            $service->service_ip = $selectedIp;
            $service->mac_address = strtoupper($macAddress);
            $service->save();

            Log::info("DHCP Binding exitoso para servicio {$service->id}", [
                'ip' => $selectedIp,
                'mac' => $macAddress,
                'router_id' => $this->routerId,
            ]);

            return [
                'success' => true,
                'message' => 'IP amarrada exitosamente',
                'ip' => $selectedIp,
                'mac' => $macAddress,
                'api_response' => $result,
            ];

        } catch (MikrotikApiException $e) {
            Log::error("Error en DHCP Binding para servicio {$service->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Crear Simple Queue para un servicio
     * 
     * @param Service $service El servicio para crear el queue
     * @return array Resultado de la operación
     */
    public function createSimpleQueue(Service $service): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado para este router');
        }

        if (!MikrotikConfigProvider::isQueueEnabled($this->routerId)) {
            throw new MikrotikApiException('Simple Queue no está habilitado para este router');
        }

        $plan = $service->plan;
        if (!$plan) {
            throw new MikrotikApiException('El servicio no tiene un plan asignado');
        }

        if (!$service->service_ip) {
            throw new MikrotikApiException('El servicio no tiene una IP asignada');
        }

        $queueName = $this->generateQueueName($service);
        $maxLimit = $this->formatMaxLimit($plan->upload_speed, $plan->download_speed);
        $comment = $this->generateComment($service);

        try {
            $result = $this->apiClient->createSimpleQueue(
                $queueName,
                $service->service_ip,
                $maxLimit,
                $comment,
                false
            );

            Log::info("Simple Queue creado para servicio {$service->id}", [
                'queue_name' => $queueName,
                'max_limit' => $maxLimit,
                'target' => $service->service_ip,
                'router_id' => $this->routerId,
            ]);

            return [
                'success' => true,
                'message' => 'Simple Queue creado exitosamente',
                'queue_name' => $queueName,
                'max_limit' => $maxLimit,
                'api_response' => $result,
            ];

        } catch (MikrotikApiException $e) {
            Log::error("Error creando Simple Queue para servicio {$service->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Provisionar servicio completo (bind IP + create queue)
     * 
     * @param Service $service El servicio a provisionar
     * @param string $selectedIp La IP seleccionada
     * @param string $macAddress La MAC address
     * @return array Resultado de la operación completa
     */
    public function provisionService(Service $service, string $selectedIp, string $macAddress): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado para este router');
        }

        $plan = $service->plan;
        if (!$plan) {
            throw new MikrotikApiException('El servicio no tiene un plan asignado');
        }

        $queueName = $this->generateQueueName($service);
        $maxLimit = $this->formatMaxLimit($plan->upload_speed, $plan->download_speed);
        $comment = $this->generateComment($service);

        try {
            // Usar el endpoint de flujo simple del microservicio
            $dhcpServer = MikrotikConfigProvider::getDhcpServer($this->routerId);
            $result = $this->apiClient->provisionService(
                $macAddress,
                $selectedIp,
                $dhcpServer,
                $queueName,
                $maxLimit,
                $comment
            );

            // Actualizar el servicio
            $service->service_ip = $selectedIp;
            $service->mac_address = strtoupper($macAddress);
            $service->save();

            Log::info("Provisión completa para servicio {$service->id}", [
                'ip' => $selectedIp,
                'mac' => $macAddress,
                'queue_name' => $queueName,
                'max_limit' => $maxLimit,
                'router_id' => $this->routerId,
            ]);

            return [
                'success' => true,
                'message' => 'Servicio provisionado exitosamente',
                'ip' => $selectedIp,
                'mac' => $macAddress,
                'queue_name' => $queueName,
                'max_limit' => $maxLimit,
                'api_response' => $result,
            ];

        } catch (MikrotikApiException $e) {
            Log::error("Error en provisión completa para servicio {$service->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Suspender un servicio (deshabilitar queue o limitar velocidad)
     */
    public function suspendService(Service $service): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado');
        }

        $queueName = $this->generateQueueName($service);
        $action = MikrotikConfigProvider::getSuspendAction($this->routerId);

        try {
            switch ($action) {
                case 'disable_queue':
                    $result = $this->apiClient->disableSimpleQueue($queueName);
                    $message = 'Queue deshabilitado';
                    break;

                case 'limit_speed':
                    $limits = MikrotikConfigProvider::getSuspendLimits($this->routerId);
                    $maxLimit = "{$limits['upload']}/{$limits['download']}";
                    $result = $this->apiClient->updateSimpleQueue($queueName, $maxLimit);
                    $message = 'Velocidad limitada';
                    break;

                case 'remove_queue':
                    $result = $this->apiClient->deleteSimpleQueue($queueName);
                    $message = 'Queue eliminado';
                    break;

                default:
                    $result = $this->apiClient->disableSimpleQueue($queueName);
                    $message = 'Queue deshabilitado';
            }

            Log::info("Servicio {$service->id} suspendido: {$message}");

            return [
                'success' => true,
                'message' => $message,
                'action' => $action,
                'api_response' => $result,
            ];

        } catch (MikrotikApiException $e) {
            Log::error("Error suspendiendo servicio {$service->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Activar un servicio (habilitar queue o restaurar velocidad)
     */
    public function activateService(Service $service): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado');
        }

        $queueName = $this->generateQueueName($service);
        $action = MikrotikConfigProvider::getActivateAction($this->routerId);

        try {
            switch ($action) {
                case 'enable_queue':
                    $result = $this->apiClient->enableSimpleQueue($queueName);
                    $message = 'Queue habilitado';
                    break;

                case 'restore_speed':
                    $plan = $service->plan;
                    $maxLimit = $this->formatMaxLimit($plan->upload_speed, $plan->download_speed);
                    $result = $this->apiClient->updateSimpleQueue($queueName, $maxLimit, null, false);
                    $message = 'Velocidad restaurada';
                    break;

                case 'create_queue':
                    $queueResult = $this->createSimpleQueue($service);
                    return $queueResult;

                default:
                    $result = $this->apiClient->enableSimpleQueue($queueName);
                    $message = 'Queue habilitado';
            }

            Log::info("Servicio {$service->id} activado: {$message}");

            return [
                'success' => true,
                'message' => $message,
                'action' => $action,
                'api_response' => $result,
            ];

        } catch (MikrotikApiException $e) {
            Log::error("Error activando servicio {$service->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Actualizar velocidad de un servicio (cuando cambia de plan)
     */
    public function updateServiceSpeed(Service $service): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado');
        }

        $plan = $service->plan;
        if (!$plan) {
            throw new MikrotikApiException('El servicio no tiene un plan asignado');
        }

        $queueName = $this->generateQueueName($service);
        $maxLimit = $this->formatMaxLimit($plan->upload_speed, $plan->download_speed);
        $comment = $this->generateComment($service);

        try {
            $result = $this->apiClient->updateSimpleQueue($queueName, $maxLimit, $comment);

            Log::info("Velocidad actualizada para servicio {$service->id}", [
                'queue_name' => $queueName,
                'max_limit' => $maxLimit,
            ]);

            return [
                'success' => true,
                'message' => 'Velocidad actualizada exitosamente',
                'max_limit' => $maxLimit,
                'api_response' => $result,
            ];

        } catch (MikrotikApiException $e) {
            Log::error("Error actualizando velocidad para servicio {$service->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Eliminar configuración de Mikrotik de un servicio
     */
    public function deprovisionService(Service $service): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado');
        }

        $results = [];

        // Eliminar queue
        if (MikrotikConfigProvider::isQueueEnabled($this->routerId)) {
            try {
                $queueName = $this->generateQueueName($service);
                $results['queue'] = $this->apiClient->deleteSimpleQueue($queueName);
            } catch (MikrotikApiException $e) {
                $results['queue_error'] = $e->getMessage();
            }
        }

        // Eliminar binding DHCP
        if (MikrotikConfigProvider::isDhcpEnabled($this->routerId) && $service->mac_address) {
            try {
                $results['dhcp'] = $this->apiClient->unbindDhcpLease($service->mac_address);
            } catch (MikrotikApiException $e) {
                $results['dhcp_error'] = $e->getMessage();
            }
        }

        Log::info("Desprovisionamiento de servicio {$service->id}", $results);

        return [
            'success' => true,
            'message' => 'Servicio desprovisionado',
            'details' => $results,
        ];
    }

    /**
     * Obtener estado del router (recursos del sistema)
     */
    public function getRouterStatus(): array
    {
        if (!MikrotikConfigProvider::isEnabled($this->routerId)) {
            throw new MikrotikApiException('El módulo Mikrotik no está habilitado');
        }

        return $this->apiClient->getSystemResources();
    }

    /**
     * Generar nombre del queue basado en el ID del servicio
     */
    private function generateQueueName(Service $service): string
    {
        $prefix = MikrotikConfigProvider::getQueueNamePrefix($this->routerId);
        return $prefix . $service->id;
    }

    /**
     * Formatear límite máximo de velocidad (upload/download)
     * Las velocidades en el plan están en Mbps
     */
    private function formatMaxLimit(int $uploadSpeed, int $downloadSpeed): string
    {
        return "{$uploadSpeed}M/{$downloadSpeed}M";
    }

    /**
     * Generar comentario para queue/binding
     */
    private function generateComment(Service $service): string
    {
        $template = MikrotikConfigProvider::getQueueCommentTemplate($this->routerId);
        
        $customer = $service->customer;
        $plan = $service->plan;

        $replacements = [
            '{service_id}' => $service->id,
            '{customer_name}' => $customer ? $customer->full_name : 'N/A',
            '{customer_id}' => $customer ? $customer->id : 'N/A',
            '{plan_name}' => $plan ? $plan->name : 'N/A',
            '{ip}' => $service->service_ip ?? 'N/A',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}
