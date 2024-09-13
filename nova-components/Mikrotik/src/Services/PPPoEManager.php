<?php

namespace Ispgo\Mikrotik\Services;

use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\MikrotikApi;
use Exception;

class PPPoEManager extends MikrotikBaseManager
{
    protected $mikrotikApi;


    /**
     * Crear un cliente PPPoE con la configuración predeterminada o personalizada.
     *
     * @param string $username Nombre de usuario PPPoE.
     * @param string $password Contraseña PPPoE.
     * @param string $service Servicio PPPoE (pppoe).
     * @param string|null $profile Perfil PPP por defecto.
     * @param string|null $remoteAddress Dirección IP estática para el cliente (opcional).
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function createPPPoEClient(string $username, string $password, string $service = 'pppoe', ?string $profile = null, ?string $remoteAddress = null): ?array
    {
        // Verificar si PPPoE está habilitado
        if (MikrotikConfigProvider::getPppEnabled() !== '1') {
            throw new Exception("PPPoE está deshabilitado en la configuración.");
        }

        // Obtener perfil PPP por defecto si no se proporciona uno
        $profile = $profile ?? MikrotikConfigProvider::getPppDefaultProfile();

        // Obtener configuración de dirección IP estática (opcional)
        $remoteAddress = $remoteAddress ?? MikrotikConfigProvider::getStaticIPAddress($username);

        // Configurar los parámetros del cliente PPPoE
        $params = [
            'name' => $username,
            'password' => $password,
            'service' => $service,
            'profile' => $profile,
            'remote-address' => $remoteAddress,
        ];

        // Crear el cliente PPPoE en el router MikroTik
        $this->init();
        return $this->mikrotikApi->execute('/ppp/secret/add', $params);
    }

    /**
     * Actualizar un cliente PPPoE existente.
     *
     * @param string $username Nombre de usuario PPPoE.
     * @param array $params Parámetros a actualizar (como password, profile, etc).
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function updatePPPoEClient(string $username, array $params): ?array
    {
        // Verificar si PPPoE está habilitado
        if (MikrotikConfigProvider::getPppEnabled() !== '1') {
            throw new Exception("PPPoE está deshabilitado en la configuración.");
        }

        // Añadir el nombre del usuario al array de parámetros
        $params['name'] = $username;

        // Ejecutar el comando para actualizar el cliente PPPoE
        return $this->mikrotikApi->execute('/ppp/secret/set', $params);
    }

    /**
     * Eliminar un cliente PPPoE por su nombre de usuario.
     *
     * @param string $username Nombre de usuario PPPoE.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function deletePPPoEClient(string $username): ?array
    {
        // Ejecutar el comando para eliminar el cliente PPPoE
        return $this->mikrotikApi->execute('/ppp/secret/remove', [
            'name' => $username,
        ]);
    }

    /**
     * Obtener la lista de clientes PPPoE.
     *
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function listPPPoEClients(): ?array
    {
        // Obtener la lista de clientes PPPoE
        return $this->mikrotikApi->execute('/ppp/secret/print', []);
    }
}
