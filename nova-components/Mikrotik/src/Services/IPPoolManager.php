<?php

namespace Ispgo\Mikrotik\Services;

use Ispgo\Mikrotik\Services\MikrotikBaseManager;

class IPPoolManager extends MikrotikBaseManager
{
    protected $mikrotikApi;



    /**
     * Crear un nuevo pool de IP en MikroTik.
     *
     * @param string $name
     * @param string $address
     * @param string|null $comment
     * @return array|null
     * @throws Exception
     */
    public function createPool(string $name, string $address, ?string $comment = null): ?array
    {
        try {
            // Preparar los parámetros para crear el pool de IP
            $params = [
                'name' => $name,
                'ranges' => $address,  // En MikroTik, el campo es "ranges" para definir las direcciones IP
                'comment' => $comment ?? ''
            ];
            $this->init();
            return $this->mikrotikApi->execute('/ip/pool/add', $params);
        } catch (Exception $e) {
            throw new Exception('Error creating IP pool: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un pool de IP existente en MikroTik.
     *
     * @param string $poolId
     * @return array|null
     * @throws Exception
     */
    public function deletePool(string $poolId): ?array
    {
        try {
           $this->init();
            return $this->mikrotikApi->execute('/ip/pool/remove', [
                '.id' => $poolId  // En MikroTik, se utiliza el ID para eliminar
            ]);
        } catch (Exception $e) {
            throw new Exception('Error deleting IP pool: ' . $e->getMessage());
        }
    }

    /**
     * Listar todos los pools de IP en MikroTik.
     *
     * @return array|null
     * @throws Exception
     */
    public function listPools(): ?array
    {
        try {
            // Ejecutar el comando para listar todos los pools de IP
            $this->init();
            return $this->mikrotikApi->execute('/ip/pool/print', []);
        } catch (Exception $e) {
            throw new Exception('Error fetching IP pools: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un pool de IP existente en MikroTik.
     *
     * @param string $poolId
     * @param array $params
     * @return array|null
     * @throws Exception
     */
    public function updatePool(string $poolId, array $params): ?array
    {
        try {
            // Agregar el ID del pool a los parámetros
            $params['.id'] = $poolId;

            // Ejecutar el comando para actualizar el pool de IP
            return $this->mikrotikApi->execute('/ip/pool/set', $params);
        } catch (Exception $e) {
            throw new Exception('Error updating IP pool: ' . $e->getMessage());
        }
    }
}
