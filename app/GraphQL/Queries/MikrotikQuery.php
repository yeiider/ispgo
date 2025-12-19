<?php

namespace App\GraphQL\Queries;

use App\Models\Services\Service;
use Ispgo\Mikrotik\Services\MikrotikApiClient;
use Ispgo\Mikrotik\Services\MikrotikProvisionService;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\Exceptions\MikrotikApiException;

/**
 * Resolvers de GraphQL para consultas de Mikrotik
 */
class MikrotikQuery
{
    /**
     * Obtener DHCP leases del router
     */
    public function dhcpLeases($root, array $args): array
    {
        $routerId = (int) $args['router_id'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return [
                'success' => false,
                'message' => 'El módulo Mikrotik no está habilitado para este router',
                'count' => 0,
                'leases' => [],
            ];
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $response = $client->getDhcpLeases();

            $leases = [];
            if (isset($response['leases']) && is_array($response['leases'])) {
                foreach ($response['leases'] as $lease) {
                    $leases[] = [
                        'address' => $lease['address'] ?? '',
                        'mac_address' => $lease['mac_address'] ?? $lease['mac-address'] ?? '',
                        'server' => $lease['server'] ?? null,
                        'status' => $lease['status'] ?? null,
                        'host_name' => $lease['host_name'] ?? $lease['host-name'] ?? null,
                        'comment' => $lease['comment'] ?? null,
                        'is_static' => isset($lease['dynamic']) ? $lease['dynamic'] === 'false' : null,
                        'expires_after' => $lease['expires_after'] ?? $lease['expires-after'] ?? null,
                    ];
                }
            }

            return [
                'success' => true,
                'message' => 'Leases obtenidos exitosamente',
                'count' => count($leases),
                'leases' => $leases,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'count' => 0,
                'leases' => [],
            ];
        }
    }

    /**
     * Obtener DHCP servers del router
     */
    public function dhcpServers($root, array $args): array
    {
        $routerId = (int) $args['router_id'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return [
                'success' => false,
                'message' => 'El módulo Mikrotik no está habilitado para este router',
                'count' => 0,
                'servers' => [],
            ];
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $response = $client->getDhcpServers();

            $servers = [];
            if (isset($response['servers']) && is_array($response['servers'])) {
                foreach ($response['servers'] as $server) {
                    $servers[] = [
                        'name' => $server['name'] ?? '',
                        'interface' => $server['interface'] ?? '',
                        'lease_time' => $server['lease_time'] ?? $server['lease-time'] ?? null,
                        'address_pool' => $server['address_pool'] ?? $server['address-pool'] ?? null,
                    ];
                }
            }

            return [
                'success' => true,
                'message' => 'Servidores DHCP obtenidos exitosamente',
                'count' => count($servers),
                'servers' => $servers,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'count' => 0,
                'servers' => [],
            ];
        }
    }

    /**
     * Buscar lease por MAC address
     */
    public function findLeaseByMac($root, array $args): ?array
    {
        $routerId = (int) $args['router_id'];
        $macAddress = $args['mac_address'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return null;
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $lease = $client->findLeaseByMac($macAddress);

            if (!$lease) {
                return null;
            }

            return [
                'address' => $lease['address'] ?? '',
                'mac_address' => $lease['mac_address'] ?? $lease['mac-address'] ?? '',
                'server' => $lease['server'] ?? null,
                'status' => $lease['status'] ?? null,
                'host_name' => $lease['host_name'] ?? $lease['host-name'] ?? null,
                'comment' => $lease['comment'] ?? null,
                'is_static' => isset($lease['dynamic']) ? $lease['dynamic'] === 'false' : null,
                'expires_after' => $lease['expires_after'] ?? $lease['expires-after'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return null;
        }
    }

    /**
     * Buscar lease por IP
     */
    public function findLeaseByIp($root, array $args): ?array
    {
        $routerId = (int) $args['router_id'];
        $ipAddress = $args['ip_address'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return null;
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $lease = $client->findLeaseByIp($ipAddress);

            if (!$lease) {
                return null;
            }

            return [
                'address' => $lease['address'] ?? '',
                'mac_address' => $lease['mac_address'] ?? $lease['mac-address'] ?? '',
                'server' => $lease['server'] ?? null,
                'status' => $lease['status'] ?? null,
                'host_name' => $lease['host_name'] ?? $lease['host-name'] ?? null,
                'comment' => $lease['comment'] ?? null,
                'is_static' => isset($lease['dynamic']) ? $lease['dynamic'] === 'false' : null,
                'expires_after' => $lease['expires_after'] ?? $lease['expires-after'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return null;
        }
    }

    /**
     * Obtener Simple Queues del router
     */
    public function simpleQueues($root, array $args): array
    {
        $routerId = (int) $args['router_id'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return [
                'success' => false,
                'message' => 'El módulo Mikrotik no está habilitado para este router',
                'count' => 0,
                'queues' => [],
            ];
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $response = $client->getSimpleQueues();

            $queues = [];
            if (isset($response['queues']) && is_array($response['queues'])) {
                foreach ($response['queues'] as $queue) {
                    $queues[] = $this->formatQueue($queue);
                }
            }

            return [
                'success' => true,
                'message' => 'Queues obtenidos exitosamente',
                'count' => count($queues),
                'queues' => $queues,
            ];

        } catch (MikrotikApiException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'count' => 0,
                'queues' => [],
            ];
        }
    }

    /**
     * Buscar queue por nombre
     */
    public function findQueue($root, array $args): ?array
    {
        $routerId = (int) $args['router_id'];
        $name = $args['name'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return null;
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $queue = $client->findQueueByName($name);

            if (!$queue) {
                return null;
            }

            return $this->formatQueue($queue);

        } catch (MikrotikApiException $e) {
            return null;
        }
    }

    /**
     * Obtener recursos del sistema
     */
    public function systemResources($root, array $args): ?array
    {
        $routerId = (int) $args['router_id'];

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return null;
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $response = $client->getSystemResources();

            if (!isset($response['data'])) {
                return $response;
            }

            $data = $response['data'];

            return [
                'uptime' => $data['uptime'] ?? null,
                'version' => $data['version'] ?? null,
                'cpu_load' => $data['cpu_load'] ?? $data['cpu-load'] ?? null,
                'free_memory' => $data['free_memory'] ?? $data['free-memory'] ?? null,
                'total_memory' => $data['total_memory'] ?? $data['total-memory'] ?? null,
                'free_hdd' => $data['free_hdd'] ?? $data['free-hdd-space'] ?? null,
                'total_hdd' => $data['total_hdd'] ?? $data['total-hdd-space'] ?? null,
                'board_name' => $data['board_name'] ?? $data['board-name'] ?? null,
                'model' => $data['model'] ?? $data['platform'] ?? null,
            ];

        } catch (MikrotikApiException $e) {
            return null;
        }
    }

    /**
     * Obtener estado de configuración
     */
    public function configStatus($root, array $args): array
    {
        $routerId = (int) $args['router_id'];

        $credentials = MikrotikConfigProvider::getRouterCredentials($routerId);

        return [
            'enabled' => MikrotikConfigProvider::isEnabled($routerId),
            'dhcp_enabled' => MikrotikConfigProvider::isDhcpEnabled($routerId),
            'queue_enabled' => MikrotikConfigProvider::isQueueEnabled($routerId),
            'api_url' => MikrotikConfigProvider::getApiBaseUrl($routerId),
            'router_host' => $credentials['host'] ?? null,
        ];
    }

    /**
     * Obtener queue de un servicio específico
     */
    public function serviceQueue($root, array $args): ?array
    {
        $serviceId = (int) $args['service_id'];
        $service = Service::find($serviceId);

        if (!$service) {
            return null;
        }

        $routerId = $service->router_id;

        if (!MikrotikConfigProvider::isEnabled($routerId)) {
            return null;
        }

        try {
            $client = new MikrotikApiClient($routerId);
            $prefix = MikrotikConfigProvider::getQueueNamePrefix($routerId);
            $queueName = $prefix . $service->id;

            $queue = $client->findQueueByName($queueName);

            if (!$queue) {
                return null;
            }

            return $this->formatQueue($queue);

        } catch (MikrotikApiException $e) {
            return null;
        }
    }

    /**
     * Formatear datos de un queue
     */
    private function formatQueue(array $queue): array
    {
        return [
            'id' => $queue['.id'] ?? $queue['id'] ?? null,
            'name' => $queue['name'] ?? '',
            'target' => $queue['target'] ?? '',
            'max_limit' => $queue['max_limit'] ?? $queue['max-limit'] ?? null,
            'burst_limit' => $queue['burst_limit'] ?? $queue['burst-limit'] ?? null,
            'disabled' => isset($queue['disabled']) ? $queue['disabled'] === 'true' : null,
            'comment' => $queue['comment'] ?? null,
            'bytes' => $queue['bytes'] ?? null,
            'packets' => $queue['packets'] ?? null,
        ];
    }
}
