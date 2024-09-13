<?php

namespace Ispgo\Mikrotik\Services;

use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\MikrotikApi;
use Exception;

class SimpleQueueManager extends MikrotikBaseManager
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->init();

    }

    /**
     * Crear una Simple Queue para un cliente específico.
     *
     * @param string $target Dirección IP o rango de IP del cliente.
     * @param string $name Nombre de la cola simple.
     * @param string $uploadLimit Límite de subida (por ejemplo, 10M).
     * @param string $downloadLimit Límite de bajada (por ejemplo, 10M).
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function createSimpleQueue(string $target, string $name, ?string $uploadLimit = null, ?string $downloadLimit = null): ?array
    {
        // Verificar si Simple Queue está habilitado en la configuración
        if (MikrotikConfigProvider::getSimpleQueueEnabled() !== '1') {
            throw new Exception("Simple Queue está deshabilitado en la configuración.");
        }

        // Obtener límites predeterminados de subida y bajada si no se especificaron
        $uploadLimit = $uploadLimit ?? MikrotikConfigProvider::getSimpleQueueLimitUpload();
        $downloadLimit = $downloadLimit ?? MikrotikConfigProvider::getSimpleQueueLimitDownload();

        // Obtener el tipo de cola (opcional)
        $queueType = MikrotikConfigProvider::getSimpleQueueType();

        // Configuración para la simple queue
        $params = [
            'name' => $name,
            'target' => $target,
            'max-limit' => $downloadLimit . '/' . $uploadLimit,
            'queue' => $queueType ?: 'default',
        ];

        return $this->mikrotikApi->execute('/queue/simple/add', $params);
    }

    /**
     * Eliminar una Simple Queue por nombre.
     *
     * @param string $name Nombre de la cola simple.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function deleteSimpleQueue(string $name): ?array
    {
        // Ejecutar el comando para eliminar la simple queue
        return $this->mikrotikApi->execute('/queue/simple/remove', [
            'name' => $name,
        ]);
    }


    /**
     * Crear una Simple Queue para un cliente específico basado en los datos formateados.
     *
     * @param string $target Dirección IP o rango de IP del cliente.
     * @param array $formattedData Datos formateados desde PlanFormatter.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function createSimpleQueueFromFormattedData(string $target, array $formattedData): ?array
    {
        // Verificar si Simple Queue está habilitado en la configuración
        if (!MikrotikConfigProvider::getSimpleQueueEnabled()) {
            throw new Exception("Simple Queue está deshabilitado en la configuración.");
        }

        // Obtener límites de subida y bajada desde el array formateado
        $uploadLimit = $formattedData['upload_speed'] . 'M';
        $downloadLimit = $formattedData['download_speed'] . 'M';

        // Obtener el tipo de cola desde el array formateado o configuraciones
        $queueType = $formattedData['queue_type'] ?? MikrotikConfigProvider::getSimpleQueueType();

        // Configuración para la simple queue basada en los datos formateados
        $params = [
            'name' => $formattedData['service_name'],
            'target' => $target,
            'max-limit' => $downloadLimit . '/' . $uploadLimit,
            'limit-at' => $downloadLimit . '/' . $uploadLimit,
            'comment' => $formattedData['plan_name'] ?? '',
        ];

        Log::info(json_encode($params));


        // Ejecutar el comando para crear la simple queue en el router MikroTik
        return $this->mikrotikApi->execute('/queue/simple/add', $params);
    }

    /**
     * Actualizar una Simple Queue existente.
     *
     * @param string $target Dirección IP o rango de IP del cliente.
     * @param string $name Nombre de la cola simple.
     * @param string|null $uploadLimit Límite de subida (por ejemplo, 10M).
     * @param string|null $downloadLimit Límite de bajada (por ejemplo, 10M).
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function updateSimpleQueue(string $target, string $name, ?string $uploadLimit = null, ?string $downloadLimit = null): ?array
    {
        // Verificar si Simple Queue está habilitado en la configuración
        if (MikrotikConfigProvider::getSimpleQueueEnabled()) {
            throw new Exception("Simple Queue está deshabilitado en la configuración.");
        }

        // Obtener límites predeterminados de subida y bajada si no se especificaron
        $uploadLimit = $uploadLimit ?? MikrotikConfigProvider::getSimpleQueueLimitUpload();
        $downloadLimit = $downloadLimit ?? MikrotikConfigProvider::getSimpleQueueLimitDownload();

        // Obtener el tipo de cola (opcional)
        $queueType = MikrotikConfigProvider::getSimpleQueueType();

        // Configuración para la simple queue
        $params = [
            'name' => $name,
            'target' => $target,
            'max-limit' => $downloadLimit . '/' . $uploadLimit,
            'queue' => $queueType ?: 'default',
        ];

        // Ejecutar el comando para actualizar la simple queue en el router MikroTik
        return $this->mikrotikApi->execute('/queue/simple/set', $params);
    }
}
