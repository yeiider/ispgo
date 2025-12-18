<?php

namespace Ispgo\Mikrotik\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\Exceptions\MikrotikApiException;

/**
 * Cliente HTTP para comunicarse con el microservicio de Mikrotik
 *
 * Este cliente abstrae las llamadas HTTP al microservicio externo
 * que maneja la comunicación directa con los routers Mikrotik.
 */
class MikrotikApiClient
{
    private string $baseUrl;
    private int $timeout;
    private array $credentials;
    private int $routerId;
    private bool $debugMode;

    public function __construct(int $routerId = 0)
    {
        $this->routerId = $routerId;
        $this->baseUrl = rtrim(MikrotikConfigProvider::getApiBaseUrl($routerId), '/');
        $this->timeout = MikrotikConfigProvider::getApiTimeout($routerId);
        $this->credentials = MikrotikConfigProvider::getRouterCredentials($routerId);
        $this->debugMode = MikrotikConfigProvider::isDebugMode($routerId);
    }

    /**
     * Obtener los DHCP leases del router
     */
    public function getDhcpLeases(): array
    {
        return $this->post('/mikrotik/dhcp/leases', $this->credentials);
    }

    /**
     * Buscar un lease específico por MAC address
     */
    public function findLeaseByMac(string $macAddress): ?array
    {
        $leases = $this->getDhcpLeases();

        if (!isset($leases['leases']) || !is_array($leases['leases'])) {
            return null;
        }

        foreach ($leases['leases'] as $lease) {
            if (isset($lease['mac_address']) &&
                strtoupper($lease['mac_address']) === strtoupper($macAddress)) {
                return $lease;
            }
        }

        return null;
    }

    /**
     * Buscar un lease específico por IP
     */
    public function findLeaseByIp(string $ipAddress): ?array
    {
        $leases = $this->getDhcpLeases();

        if (!isset($leases['leases']) || !is_array($leases['leases'])) {
            return null;
        }

        foreach ($leases['leases'] as $lease) {
            if (isset($lease['address']) && $lease['address'] === $ipAddress) {
                return $lease;
            }
        }

        return null;
    }

    /**
     * Amarrar (bind) una IP a una MAC address
     */
    public function bindDhcpLease(string $macAddress, string $ipAddress, ?string $comment = null): array
    {
        $dhcpServer = MikrotikConfigProvider::getDhcpServer($this->routerId);

        return $this->post('/mikrotik/dhcp/bind', [
            'mac_address' => $macAddress,
            'ip_address' => $ipAddress,
            'server' => $dhcpServer,
            'comment' => $comment,
            'credentials' => $this->credentials,
        ]);
    }

    /**
     * Eliminar un binding de DHCP
     */
    public function unbindDhcpLease(string $macAddress): array
    {
        return $this->post('/mikrotik/dhcp/unbind', [
            'mac_address' => $macAddress,
            'credentials' => $this->credentials,
        ]);
    }

    /**
     * Crear un Simple Queue
     */
    public function createSimpleQueue(
        string $name,
        string $target,
        string $maxLimit,
        ?string $comment = null,
        bool $disabled = false
    ): array {
        return $this->post('/mikrotik/queues/create', [
            'name' => $name,
            'target' => $target,
            'max_limit' => $maxLimit,
            'comment' => $comment,
            'disabled' => $disabled,
            'credentials' => $this->credentials,
        ]);
    }

    /**
     * Actualizar un Simple Queue existente
     */
    public function updateSimpleQueue(
        string $name,
        ?string $maxLimit = null,
        ?string $comment = null,
        ?bool $disabled = null
    ): array {
        $data = [
            'name' => $name,
            'credentials' => $this->credentials,
        ];

        if ($maxLimit !== null) {
            $data['max_limit'] = $maxLimit;
        }
        if ($comment !== null) {
            $data['comment'] = $comment;
        }
        if ($disabled !== null) {
            $data['disabled'] = $disabled;
        }

        return $this->post('/mikrotik/queues/update', $data);
    }

    /**
     * Eliminar un Simple Queue
     */
    public function deleteSimpleQueue(string $name): array
    {
        return $this->post('/mikrotik/queues/delete', [
            'name' => $name,
            'credentials' => $this->credentials,
        ]);
    }

    /**
     * Habilitar un Simple Queue
     */
    public function enableSimpleQueue(string $name): array
    {
        return $this->updateSimpleQueue($name, null, null, false);
    }

    /**
     * Deshabilitar un Simple Queue
     */
    public function disableSimpleQueue(string $name): array
    {
        return $this->updateSimpleQueue($name, null, null, true);
    }

    /**
     * Obtener todos los Simple Queues
     */
    public function getSimpleQueues(): array
    {
        return $this->post('/mikrotik/queues/list');
    }

    /**
     * Buscar un Simple Queue por nombre
     */
    public function findQueueByName(string $name): ?array
    {
        $queues = $this->getSimpleQueues();

        if (!isset($queues['queues']) || !is_array($queues['queues'])) {
            return null;
        }

        foreach ($queues['queues'] as $queue) {
            if (isset($queue['name']) && $queue['name'] === $name) {
                return $queue;
            }
        }

        return null;
    }

    /**
     * Ejecutar el flujo completo de provisión (bind + queue)
     */
    public function provisionService(
        string $macAddress,
        string $ipAddress,
        string $queueName,
        string $maxLimit,
        ?string $comment = null
    ): array {
        $dhcpServer = MikrotikConfigProvider::getDhcpServer($this->routerId);

        return $this->post('/provision/simple-flow', [
            'lease_request' => [
                'mac_address' => $macAddress,
                'ip_address' => $ipAddress,
                'server' => $dhcpServer,
                'comment' => $comment,
                'credentials' => $this->credentials,
            ],
            'queue_request' => [
                'name' => $queueName,
                'target' => $ipAddress,
                'max_limit' => $maxLimit,
                'comment' => $comment,
                'credentials' => $this->credentials,
            ],
        ]);
    }

    /**
     * Obtener recursos del sistema (CPU, memoria, etc.)
     */
    public function getSystemResources(): array
    {
        return $this->post('/system/resources');
    }

    /**
     * Obtener información de interfaces
     */
    public function getInterfaces(): array
    {
        return $this->post('/interfaces');
    }

    /**
     * Obtener logs del sistema
     */
    public function getSystemLogs(int $limit = 50): array
    {
        return $this->post('/logs', ['limit' => $limit]);
    }

    /**
     * Realizar una petición POST al microservicio
     */
    private function post(string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;

        // Añadir credenciales si no están incluidas

        $this->logDebug("Mikrotik API Request: POST {$url}", $data);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $data);
            $responseData = $response->json() ?? [];

            $this->logDebug("Mikrotik API Response: {$response->status()}", $responseData);

            if (!$response->successful()) {
                throw new MikrotikApiException(
                    $responseData['message'] ?? 'Error en la comunicación con el microservicio',
                    $response->status()
                );
            }

            return $responseData;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->logError("Mikrotik API Connection Error: {$e->getMessage()}");
            throw new MikrotikApiException(
                'No se pudo conectar con el microservicio de Mikrotik: ' . $e->getMessage(),
                0,
                $e
            );
        } catch (\Exception $e) {
            if ($e instanceof MikrotikApiException) {
                throw $e;
            }
            $this->logError("Mikrotik API Error: {$e->getMessage()}");
            throw new MikrotikApiException(
                'Error inesperado: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Log de debug si está habilitado
     */
    private function logDebug(string $message, array $context = []): void
    {
        if ($this->debugMode) {
            // Ocultar credenciales en los logs
            if (isset($context['credentials'])) {
                $context['credentials'] = ['host' => $context['credentials']['host'] ?? '***', '...' => '***'];
            }
            if (isset($context['lease_request']['credentials'])) {
                $context['lease_request']['credentials'] = ['...' => '***'];
            }
            if (isset($context['queue_request']['credentials'])) {
                $context['queue_request']['credentials'] = ['...' => '***'];
            }
            Log::debug($message, $context);
        }
    }

    /**
     * Log de error
     */
    private function logError(string $message, array $context = []): void
    {
        Log::error($message, array_merge($context, ['router_id' => $this->routerId]));
    }
}
