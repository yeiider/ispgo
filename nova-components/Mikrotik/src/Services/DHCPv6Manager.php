<?php

namespace Ispgo\Mikrotik\Services;

use Ispgo\Mikrotik\Services\MikrotikBaseManager;
use Exception;

class DHCPv6Manager extends MikrotikBaseManager
{
    protected $mikrotikApi;

    /**
     * Crear un nuevo servidor DHCPv6 en MikroTik.
     *
     * @param string $name
     * @param string $interface
     * @param string $pool
     * @return array|null
     * @throws Exception
     */
    public function createDHCP(string $name, string $interface, string $pool): ?array
    {
        try {
            // Preparar los parámetros para crear el servidor DHCPv6
            $params = [
                'name' => $name,
                'interface' => $interface,
                'address-pool' => $pool,
            ];
            $this->init();
            return $this->mikrotikApi->execute('/ipv6/dhcp-server/add', $params);
        } catch (Exception $e) {
            throw new Exception('Error creating DHCPv6 server: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un servidor DHCPv6 existente en MikroTik.
     *
     * @param string $dhcpId
     * @return array|null
     * @throws Exception
     */
    public function deleteDHCP(string $dhcpId): ?array
    {
        try {
            $this->init();
            return $this->mikrotikApi->execute('/ipv6/dhcp-server/remove', [
                '.id' => $dhcpId  // En MikroTik, se utiliza el ID para eliminar
            ]);
        } catch (Exception $e) {
            throw new Exception('Error deleting DHCPv6 server: ' . $e->getMessage());
        }
    }

    /**
     * Listar todos los servidores DHCPv6 en MikroTik.
     *
     * @return array|null
     * @throws Exception
     */
    public function listDHCPs(): ?array
    {
        try {
            $this->init();
            return $this->mikrotikApi->execute('/ipv6/dhcp-server/print', []);
        } catch (Exception $e) {
            throw new Exception('Error fetching DHCPv6 servers: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un servidor DHCPv6 existente en MikroTik.
     *
     * @param string $dhcpId
     * @param array $params
     * @return array|null
     * @throws Exception
     */
    public function updateDHCP(string $dhcpId, array $params): ?array
    {
        try {
            // Agregar el ID del servidor DHCPv6 a los parámetros
            $params['.id'] = $dhcpId;

            // Ejecutar el comando para actualizar el servidor DHCPv6
            return $this->mikrotikApi->execute('/ipv6/dhcp-server/set', $params);
        } catch (Exception $e) {
            throw new Exception('Error updating DHCPv6 server: ' . $e->getMessage());
        }
    }
}
