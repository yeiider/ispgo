<?php

namespace Ispgo\Mikrotik\Services;

use Ispgo\Mikrotik\Services\MikrotikBaseManager;
use Exception;

class IPv6PoolManager extends MikrotikBaseManager
{
    protected $mikrotikApi;

    /**
     * Crear un nuevo pool de IPv6 en MikroTik.
     *
     * @param string $name
     * @param string $prefix
     * @param int $prefixLength
     * @param string|null $comment
     * @return array|null
     * @throws Exception
     */
    public function createPool(string $name, string $prefix, int $prefixLength, ?string $comment = null): ?array
    {
        try {
            // Preparar los parámetros para crear el pool de IPv6
            $params = [
                'name' => $name,
                'prefix' => $prefix,
                'prefix-length' => $prefixLength,
                'comment' => $comment ?? ''
            ];
            $this->init();
            return $this->mikrotikApi->execute('/ipv6/pool/add', $params);
        } catch (Exception $e) {
            throw new Exception('Error creating IPv6 pool: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un pool de IPv6 existente en MikroTik.
     *
     * @param string $poolId
     * @return array|null
     * @throws Exception
     */
    public function deletePool(string $poolId): ?array
    {
        try {
            $this->init();
            return $this->mikrotikApi->execute('/ipv6/pool/remove', [
                '.id' => $poolId  // En MikroTik, se utiliza el ID para eliminar
            ]);
        } catch (Exception $e) {
            throw new Exception('Error deleting IPv6 pool: ' . $e->getMessage());
        }
    }

    /**
     * Listar todos los pools de IPv6 en MikroTik.
     *
     * @return array|null
     * @throws Exception
     */
    public function listPools(): ?array
    {
        try {
            $this->init();
            return $this->mikrotikApi->execute('/ipv6/pool/print', []);
        } catch (Exception $e) {
            throw new Exception('Error fetching IPv6 pools: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un pool de IPv6 existente en MikroTik.
     *
     * @param string $poolId
     * @param array $params
     * @return array|null
     * @throws Exception
     */
    public function updatePool(string $poolId, array $params): ?array
    {
        try {
            $params['.id'] = $poolId;
            return $this->mikrotikApi->execute('/ipv6/pool/set', $params);
        } catch (Exception $e) {
            throw new Exception('Error updating IPv6 pool: ' . $e->getMessage());
        }
    }
}
