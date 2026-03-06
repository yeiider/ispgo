<?php

namespace App\GraphQL\Mutations;

use App\Models\Services\Service;
use Ispgo\Mikrotik\Services\MikrotikApiClient;
use Ispgo\Mikrotik\Services\MikrotikProvisionService;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\Exceptions\MikrotikApiException;
use Illuminate\Support\Facades\Log;

/**
 * Resolvers de GraphQL para mutaciones de Mikrotik
 */
class MikrotikMutation
{
    /**
     * Amarrar IP a un servicio
     */
    public function bindIp($root, array $args): array
    {
        $input = $args['input'];
        $serviceId = (int) $input['service_id'];
        $ipAddress = $input['ip_address'];
        $macAddress = $input['mac_address'];

        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'ip' => null,
                'mac' => null,
                'queue_name' => null,
                'max_limit' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->bindIpToService($service, $ipAddress, $macAddress);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'ip' => $result['ip'] ?? null,
                'mac' => $result['mac'] ?? null,
                'queue_name' => null,
                'max_limit' => null,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'ip' => null,
                'mac' => null,
                'queue_name' => null,
                'max_limit' => null,
            ];
        }
    }

    /**
     * Crear Simple Queue para un servicio
     */
    public function createQueue($root, array $args): array
    {
        $input = $args['input'];
        $serviceId = (int) $input['service_id'];

        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'ip' => null,
                'mac' => null,
                'queue_name' => null,
                'max_limit' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->createSimpleQueue($service);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'ip' => $service->service_ip,
                'mac' => $service->mac_address,
                'queue_name' => $result['queue_name'] ?? null,
                'max_limit' => $result['max_limit'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'ip' => null,
                'mac' => null,
                'queue_name' => null,
                'max_limit' => null,
            ];
        }
    }

    /**
     * Provisionar servicio completo (bind + queue)
     */
    public function provisionService($root, array $args): array
    {
        $input = $args['input'];
        $serviceId = (int) $input['service_id'];
        $ipAddress = $input['ip_address'];
        $macAddress = $input['mac_address'];

        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'ip' => null,
                'mac' => null,
                'queue_name' => null,
                'max_limit' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->provisionService($service, $ipAddress, $macAddress);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'ip' => $result['ip'] ?? null,
                'mac' => $result['mac'] ?? null,
                'queue_name' => $result['queue_name'] ?? null,
                'max_limit' => $result['max_limit'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'ip' => null,
                'mac' => null,
                'queue_name' => null,
                'max_limit' => null,
            ];
        }
    }

    /**
     * Suspender servicio
     */
    public function suspendService($root, array $args): array
    {
        $serviceId = (int) $args['service_id'];
        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->suspendService($service);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'action' => $result['action'] ?? null,
                'details' => $result['api_response'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }

    /**
     * Activar servicio
     */
    public function activateService($root, array $args): array
    {
        $serviceId = (int) $args['service_id'];
        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->activateService($service);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'action' => $result['action'] ?? null,
                'details' => $result['api_response'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }

    /**
     * Actualizar velocidad de servicio
     */
    public function updateSpeed($root, array $args): array
    {
        $input = $args['input'];
        $serviceId = (int) $input['service_id'];
        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->updateServiceSpeed($service);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'action' => 'update_speed',
                'details' => ['max_limit' => $result['max_limit'] ?? null],
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }

    /**
     * Eliminar configuración de Mikrotik de un servicio
     */
    public function deprovisionService($root, array $args): array
    {
        $serviceId = (int) $args['service_id'];
        $service = Service::find($serviceId);

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Servicio no encontrado',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $provisionService = new MikrotikProvisionService($service->router_id);
            $result = $provisionService->deprovisionService($service);

            return [
                'success' => $result['success'],
                'message' => $result['message'],
                'action' => 'deprovision',
                'details' => $result['details'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }

    /**
     * Habilitar queue específico
     */
    public function enableQueue($root, array $args): array
    {
        $routerId = (int) $args['router_id'];
        $queueName = $args['queue_name'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return [
                'success' => false,
                'message' => 'El módulo Mikrotik no está habilitado para este router',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $result = $client->enableSimpleQueue($queueName);

            return [
                'success' => true,
                'message' => 'Queue habilitado exitosamente',
                'action' => 'enable_queue',
                'details' => $result,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }

    /**
     * Deshabilitar queue específico
     */
    public function disableQueue($root, array $args): array
    {
        $routerId = (int) $args['router_id'];
        $queueName = $args['queue_name'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return [
                'success' => false,
                'message' => 'El módulo Mikrotik no está habilitado para este router',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $result = $client->disableSimpleQueue($queueName);

            return [
                'success' => true,
                'message' => 'Queue deshabilitado exitosamente',
                'action' => 'disable_queue',
                'details' => $result,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }

    /**
     * Eliminar queue específico
     */
    public function deleteQueue($root, array $args): array
    {
        $routerId = (int) $args['router_id'];
        $queueName = $args['queue_name'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return [
                'success' => false,
                'message' => 'El módulo Mikrotik no está habilitado para este router',
                'action' => null,
                'details' => null,
            ];
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $result = $client->deleteSimpleQueue($queueName);

            return [
                'success' => true,
                'message' => 'Queue eliminado exitosamente',
                'action' => 'delete_queue',
                'details' => $result,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'action' => null,
                'details' => null,
            ];
        }
    }
}
