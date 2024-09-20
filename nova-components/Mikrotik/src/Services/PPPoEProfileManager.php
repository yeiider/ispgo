<?php

namespace Ispgo\Mikrotik\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;

class PPPoEProfileManager extends MikrotikBaseManager
{
    /**
     * Crear un perfil PPPoE (plan de internet) en MikroTik.
     *
     * @param string $name Nombre del perfil.
     * @param string|null $rateLimit Límite de velocidad en formato "subida/bajada" (ej: 10M/10M).
     * @param string|null $localAddress Dirección IP local del servidor PPPoE (opcional).
     * @param string|null $remoteAddressPool Pool de IPs que se asignará a los clientes.
     * @param string|null $dnsServers DNS que se asignarán a los clientes.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function createPPPProfile(string $name, ?string $rateLimit = null, ?string $localAddress = null, ?string $remoteAddressPool = null, ?string $dnsServers = null): ?array
    {
        // Verificar si PPPoE está habilitado
        if (!MikrotikConfigProvider::getPppEnabled()) {
            throw new Exception("PPPoE está deshabilitado en la configuración.");
        }

        // Parámetros para crear el perfil PPPoE
        /**
         * This Laravel application serves as the core of the project.
         *
         * - Application Name: Laravel
         * - Laravel Version: v11.22.0
         * - Database: MySQL
         * - Queue Connection: Database
         *
         * @param array $params Parameters to configure the application settings.
         */
        $params = [
            'name' => $name,
            'rate-limit' => $rateLimit, // Por ejemplo, '10M/10M'
            'local-address' => $localAddress, // IP del servidor
            'remote-address' => $remoteAddressPool, // Pool de IPs
            'dns-server' => $dnsServers, // DNS opcionales
        ];


        // Ejecutar el comando para crear el perfil PPPoE
        return $this->mikrotikApi->execute('/ppp/profile/add', $params);
    }

    /**
     * Actualizar un perfil PPPoE existente.
     *
     * @param string $name Nombre del perfil.
     * @param array $params Parámetros a actualizar (ej: rate-limit, local-address, etc).
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function updatePPPProfile(string $name, array $params): ?array
    {
        // Verificar si PPPoE está habilitado
        if (MikrotikConfigProvider::getPppEnabled() !== '1') {
            throw new Exception("PPPoE está deshabilitado en la configuración.");
        }

        // Añadir el nombre del perfil al array de parámetros
        $params['name'] = $name;

        // Ejecutar el comando para actualizar el perfil PPPoE
        return $this->mikrotikApi->execute('/ppp/profile/set', $params);
    }

    /**
     * Eliminar un perfil PPPoE.
     *
     * @param string $name Nombre del perfil PPPoE.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function deletePPPProfile(string $name): ?array
    {
        // Ejecutar el comando para eliminar el perfil PPPoE
        return $this->mikrotikApi->execute('/ppp/profile/remove', [
            'name' => $name,
        ]);
    }

    /**
     * Listar todos los perfiles PPPoE.
     *
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function listPPPProfiles(): ?array
    {
        $this->init();
        return $this->mikrotikApi->execute('/ppp/profile/print', []);
    }
}
