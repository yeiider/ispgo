<?php

namespace Ispgo\Smartolt\Services;

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

    private function request(string $endpoint, array $payload = []): Response
    {
        try {
            return Http::withHeaders(['X-Token' => $this->token])
                ->post($this->baseUrl . $endpoint, $payload);
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
        return $this->request('api/onu/bulk_enable', $payload);
    }

    /**
     * Deshabilitar ONUs en lote.
     *
     * @throws ConnectionException
     */
    public function disableBulk(array $payload): Response
    {
        $this->validatePayload($payload);
        return $this->request('api/onu/bulk_disable', $payload);
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
}
