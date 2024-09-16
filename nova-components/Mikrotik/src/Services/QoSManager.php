<?php

namespace Ispgo\Mikrotik\Services;

use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\MikrotikApi;
use Exception;

class QoSManager extends MikrotikBaseManager
{
    protected $mikrotikApi;



    /**
     * Aplicar QoS a un cliente.
     *
     * @param string $target IP o rango de IP del cliente.
     * @param string $name Nombre de la regla de QoS.
     * @param string|null $maxLimit Límite máximo de ancho de banda.
     * @param int|null $priority Prioridad del tráfico.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function applyQoS(string $target, string $name, ?string $maxLimit = null, ?int $priority = null): ?array
    {
        // Verificar si QoS está habilitado
        if (MikrotikConfigProvider::getQoSEnabled() !== '1') {
            throw new Exception("QoS está deshabilitado en la configuración.");
        }

        // Obtener configuraciones de QoS por defecto
        $maxLimit = $maxLimit ?? MikrotikConfigProvider::getQoSMaxLimit();
        $priority = $priority ?? MikrotikConfigProvider::getQoSPriority();

        // Crear una simple queue con los parámetros de QoS
        $params = [
            'name' => $name,
            'target' => $target,
            'max-limit' => $maxLimit,
            'priority' => $priority,
        ];

        // Aplicar la regla de QoS utilizando las colas simples
        return $this->mikrotikApi->execute('/queue/simple/add', $params);
    }

    /**
     * Actualizar una regla de QoS existente.
     *
     * @param string $name Nombre de la regla de QoS.
     * @param array $params Parámetros a actualizar.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function updateQoS(string $name, array $params): ?array
    {
        // Verificar si QoS está habilitado
        if (MikrotikConfigProvider::getQoSEnabled() !== '1') {
            throw new Exception("QoS está deshabilitado en la configuración.");
        }

        // Añadir el nombre de la regla al array de parámetros
        $params['name'] = $name;

        // Ejecutar el comando para actualizar la regla de QoS
        return $this->mikrotikApi->execute('/queue/simple/set', $params);
    }

    /**
     * Eliminar una regla de QoS por su nombre.
     *
     * @param string $name Nombre de la regla de QoS.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function deleteQoS(string $name): ?array
    {
        // Ejecutar el comando para eliminar la regla de QoS
        return $this->mikrotikApi->execute('/queue/simple/remove', [
            'name' => $name,
        ]);
    }
}
