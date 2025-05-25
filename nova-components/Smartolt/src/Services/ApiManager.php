<?php

namespace Ispgo\Smartolt\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;
use Illuminate\Http\Client\Response;

class ApiManager
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = ProviderSmartOlt::getUrl();
        $this->token = ProviderSmartOlt::getToken();
    }

    private function request(string $endpoint, array $payload = [], bool $asForm = false, string $method = 'post'): Response
    {
        try {
            $request = Http::withHeaders(['X-Token' => $this->token]);

            // Configurar como form-data si es necesario
            if ($asForm) {
                $request = $request->asForm();
            }

            if ($method === 'get') {
                return $request->get($this->baseUrl . $endpoint, $payload);
            }

            return $request->post($this->baseUrl . $endpoint, $payload);
        } catch (ConnectionException $e) {
            // Aquí puedes loguear o manejar la excepción según sea necesario
            throw new \Exception("Error al conectar con SmartOLT: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Habilitar ONUs en lote.
     *
     * @throws ConnectionException
     */
    public function enableBulk(array $payload): Response
    {
        $this->validatePayload($payload);
        return $this->request('api/onu/bulk_enable', $payload, true);
    }

    /**
     * Deshabilitar ONUs en lote.
     *
     * @throws ConnectionException
     */
    public function disableBulk(array $payload): Response
    {
        $this->validatePayload($payload);
        return $this->request('api/onu/bulk_disable', $payload, true);
    }

    /**
     * Habilitar una ONU por su número de serie.
     *
     * @throws \Exception
     */
    public function enableOnu(string $sn): Response
    {
        $this->validateSerialNumber($sn);
        return $this->request('api/onu/enable/' . $sn);
    }

    /**
     * Deshabilitar una ONU por su número de serie.
     *
     * @throws \Exception
     */
    public function disableOnu(string $sn): Response
    {
        $this->validateSerialNumber($sn);
        return $this->request('api/onu/disable/' . $sn);
    }

    /**
     * Update Seed Profile una ONU por su número de serie.
     *
     * @throws ConnectionException
     * @throws \Exception
     */
    public function updatePlan(string $sn, $payload): Response
    {
        $this->validateSerialNumber($sn);
        return $this->request('api/onu/update_onu_speed_profiles/' . $sn, $payload);
    }

    /**
     * Validar el payload antes de enviarlo.
     *
     * @param array $payload
     * @throws \InvalidArgumentException
     */
    private function validatePayload(array $payload): void
    {
        if (empty($payload)) {
            throw new \InvalidArgumentException("El payload no puede estar vacío.");
        }
        // Agrega otras validaciones según los requisitos de la API
    }


    public function getAllOnus(): PromiseInterface|Response
    {

        $endpoint = 'api/onu/get_all_onus_details';
        $queryParams = ['olt_id' => 19];

        try {
            $response = Http::withHeaders(['X-Token' => $this->token])
                ->get($this->baseUrl . $endpoint, $queryParams);

            return $response;
        } catch (ConnectionException $e) {
            throw new \Exception("Error al conectar con SmartOLT: " . $e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * Obtiene la lista de zonas del sistema SmartOLT.
     *
     * @return Response Retorna la promesa o respuesta HTTP con las zonas
     * @throws \Exception Si hay error de conexión con el servidor SmartOLT
     */
    public function getZones(): Response
    {
        $endpoint = 'api/system/get_zones';
        try {
            return $this->request($endpoint, [], false, 'get');
        } catch (ConnectionException $e) {
            throw new \Exception("Error al conectar con SmartOLT: " . $e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * Validar el número de serie de la ONU.
     *
     * @param string $sn
     * @throws \InvalidArgumentException
     */
    private function validateSerialNumber(string $sn): void
    {
        if (empty($sn) || strlen($sn) < 3) {
            throw new \InvalidArgumentException("El número de serie de la ONU no es válido.");
        }
    }

    /**
     * Validar el external_id.
     *
     * @param string $externalId
     * @throws \InvalidArgumentException
     */
    private function validateExternalId(string $externalId): void
    {
        if (empty($externalId)) {
            throw new \InvalidArgumentException("El external_id no puede estar vacío.");
        }
    }

    /**
     * Obtener detalles de la ONU por external_id.
     *
     * @param string $externalId
     * @return Response
     * @throws \Exception
     */
    public function getOnuDetailsByExternalId(string $externalId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/get_onu_details/' . $externalId, [], false, 'get');
    }

    /**
     * Obtener estado completo de la ONU por external_id.
     *
     * @param string $externalId
     * @return Response
     * @throws \Exception
     */
    public function getOnuFullStatusByExternalId(string $externalId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/get_onu_full_status_info/' . $externalId, [], false, 'get');
    }

    /**
     * Obtener configuración actual de la ONU por external_id.
     *
     * @param string $externalId
     * @return Response
     * @throws \Exception
     */
    public function getOnuRunningConfigByExternalId(string $externalId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/get_onu_running_config/' . $externalId, [], false, 'get');
    }

    /**
     * Obtener gráfico de señal óptica por external_id.
     *
     * @param string $externalId
     * @return Response
     * @throws \Exception
     */
    public function getOnuSignalGraphByExternalId(string $externalId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/get_onu_signal_graph/' . $externalId, [], false, 'get');
    }

    /**
     * Obtener gráfico de tráfico por external_id.
     *
     * @param string $externalId
     * @param string $graphType
     * @return Response
     * @throws \Exception
     */
    public function getOnuTrafficGraphByExternalId(string $externalId, string $graphType = 'hourly'): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/get_onu_traffic_graph/' . $externalId . '/' . $graphType, [], false, 'get');
    }

    /**
     * Reiniciar la ONU por external_id.
     *
     * @param string $externalId
     * @return Response
     * @throws \Exception
     */
    public function rebootOnuByExternalId(string $externalId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/reboot', ['external_id' => $externalId]);
    }

    /**
     * Restaurar configuración de fábrica por external_id.
     *
     * @param string $externalId
     * @return Response
     * @throws \Exception
     */
    public function restoreOnuFactoryDefaultsByExternalId(string $externalId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/restore_onu_factory_defaults_by_onu_external_id', ['external_id' => $externalId]);
    }

    /**
     * Cambiar perfil de velocidad por external_id.
     *
     * @param string $externalId
     * @param int $speedProfileId
     * @return Response
     * @throws \Exception
     */
    public function updateOnuSpeedProfileByExternalId(string $externalId, int $speedProfileId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/update_onu_speed_profiles_by_onu_external_id', [
            'external_id' => $externalId,
            'speed_profile_id' => $speedProfileId
        ]);
    }

    /**
     * Cambiar VLAN principal por external_id.
     *
     * @param string $externalId
     * @param int $vlanId
     * @return Response
     * @throws \Exception
     */
    public function updateOnuMainVlanByExternalId(string $externalId, int $vlanId): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/update_onu_main_vlan_id_by_onu_external_id', [
            'external_id' => $externalId,
            'vlan_id' => $vlanId
        ]);
    }

    /**
     * Cambiar modo WAN por external_id.
     *
     * @param string $externalId
     * @param string $wanMode
     * @return Response
     * @throws \Exception
     */
    public function setOnuWanModeByExternalId(string $externalId, string $wanMode): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/set_onu_wan_mode_by_onu_external_id', [
            'external_id' => $externalId,
            'wan_mode' => $wanMode
        ]);
    }

    /**
     * Obtener lista de OLTs.
     *
     * @return Response
     * @throws \Exception
     */
    public function getOlts(): Response
    {
        try {
            return $this->request('api/system/get_olts', [], false, 'get');
        } catch (ConnectionException $e) {
            throw new \Exception("Error al conectar con SmartOLT: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Obtener detalles de las tarjetas de una OLT por ID.
     *
     * @param int $oltId
     * @return Response
     * @throws \Exception
     */
    public function getOltCardsDetails(int $oltId): Response
    {
        try {
            return $this->request('api/system/get_olt_cards_details/' . $oltId, [], false, 'get');
        } catch (ConnectionException $e) {
            throw new \Exception("Error al obtener detalles de las tarjetas de la OLT: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Obtener ONUs no configuradas para una OLT por ID.
     *
     * @param int $oltId
     * @return Response
     * @throws \Exception
     */
    public function getUnconfiguredOnusForOlt(int $oltId): Response
    {
        try {
            return $this->request('api/onu/unconfigured_onus_for_olt/' . $oltId, [], false, 'get');
        } catch (ConnectionException $e) {
            throw new \Exception("Error al obtener ONUs no configuradas: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Obtener Vlans por ID de OLT.
     *
     * @param int $oltId
     * @return Response
     * @throws \Exception
     */
    public function getVlansByOltId(int $oltId): Response
    {
        try {
            return $this->request('api/olt/get_vlans/' . $oltId, [], false, 'get');
        }catch (ConnectionException $e) {
            throw new \Exception("Error al obtener VLANs: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Configurar IP de gestión DHCP para ONU por external_id.
     *
     * @param string $externalId
     * @param int $vlan
     * @return Response
     * @throws \Exception
     */
    public function setOnuManagementIpDhcpByExternalId(string $externalId, int $vlan): Response
    {
        $this->validateExternalId($externalId);
        return $this->request('api/onu/set_onu_mgmt_ip_dhcp/' . $externalId, ['vlan' => $vlan], true);
    }

    /**
     * Autorizar una ONU nueva.
     *
     * @param array $payload Datos de la ONU a autorizar
     * @return Response
     * @throws \Exception
     */
    public function authorizeOnu(array $payload): Response
    {
        return $this->request('api/onu/authorize_onu', $payload, true);
    }


}
