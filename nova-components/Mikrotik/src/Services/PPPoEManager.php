<?php

namespace Ispgo\Mikrotik\Services;

use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\MikrotikApi;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
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
        if (!MikrotikConfigProvider::getEnabled()) {
            throw new Exception("PPPoE está deshabilitado en la configuración.");
        }
        Log::info("PPPoE Crear PPPoE");

        $profile = $profile ?? "default";
        $remoteAddress = MikrotikConfigProvider::getStaticIpEnabled() ? $remoteAddress : null;

        $params = [
            'name' => $username,
            'password' => $password,
            'service' => $service,
        ];
        if (MikrotikConfigProvider::getIpPoolEnabled()) {
            $params['profile'] = $profile;
        }
        if ($remoteAddress) {
            $params['remote-address'] = $remoteAddress;
        }

        return $this->mikrotikApi->execute('/ppp/secret/add', $params);
    }



    /**
     * Habilitar un cliente PPPoE existente.
     *
     * @param string $username Nombre de usuario PPPoE.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function enablePPPoEClient(string $username): ?array
    {
        try {
            $this->init();
            // Buscar el ID del cliente por el nombre de usuario
            $clientId = $this->mikrotikApi->findPPPoEClientIdByUsername($username,'/ppp/secret/print');

            if (!$clientId) {
                throw new Exception("Cliente PPPoE no encontrado: $username");
            }

            // Ejecutar el comando para habilitar el cliente PPPoE
            return $this->mikrotikApi->execute('/ppp/secret/set', [
                '.id' => $clientId,  // Se utiliza el ID del cliente
                'disabled' => 'no',  // Habilitar el cliente
            ]);
        } catch (Exception $e) {
            throw new Exception('Error al habilitar el cliente PPPoE: ' . $e->getMessage());
        }
    }

    /**
     * Deshabilitar un cliente PPPoE existente.
     *
     * @param string $username Nombre de usuario PPPoE.
     * @return array|null Respuesta de la API de MikroTik.
     * @throws Exception
     */
    public function disablePPPoEClient(string $username): ?array
    {

        try {
            $this->init();
            // Buscar el ID del cliente por el nombre de usuario
            $clientId = $this->mikrotikApi->findPPPoEClientIdByUsername($username,'/ppp/secret/print');

            if (!$clientId) {
                throw new Exception("Cliente PPPoE no encontrado: $username");
            }

            // Ejecutar el comando para deshabilitar el cliente PPPoE
            return $this->mikrotikApi->execute('/ppp/secret/set', [
                '.id' => $clientId,  // Se utiliza el ID del cliente
                'disabled' => 'yes',  // Deshabilitar el cliente
            ]);
        } catch (Exception $e) {
            throw new Exception('Error al deshabilitar el cliente PPPoE: ' . $e->getMessage());
        }
    }
}
