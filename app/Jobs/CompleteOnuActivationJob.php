<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class CompleteOnuActivationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        protected string $sn,
    ) {}

    public function handle(ApiManager $apiManager): void
    {
        Log::info("CompleteOnuActivationJob: iniciando para SN: {$this->sn}");

        // Paso 1: Configurar IP de gestión DHCP
        try {
            $vlan = ProviderSmartOlt::getDefaultVlan();
            $response = $apiManager->setOnuManagementIpDhcpByExternalId($this->sn, $vlan);
            $data = $response->json();
            if (($data['status'] ?? false) !== true) {
                Log::warning('CompleteOnuActivationJob: set_onu_mgmt_ip_dhcp falló', [
                    'sn' => $this->sn,
                    'response' => $data,
                ]);
            } else {
                Log::info('CompleteOnuActivationJob: set_onu_mgmt_ip_dhcp OK', ['sn' => $this->sn]);
            }
        } catch (\Exception $e) {
            Log::error('CompleteOnuActivationJob: error en set_onu_mgmt_ip_dhcp', [
                'sn' => $this->sn,
                'error' => $e->getMessage(),
            ]);
        }

        // Paso 2: Habilitar TR069
        try {
            $response = $apiManager->enableTr069($this->sn, ProviderSmartOlt::getTr069Profile());
            $data = $response->json();
            if (($data['status'] ?? false) !== true) {
                Log::warning('CompleteOnuActivationJob: enable_tr069 falló', [
                    'sn' => $this->sn,
                    'response' => $data,
                ]);
            } else {
                Log::info('CompleteOnuActivationJob: enable_tr069 OK', ['sn' => $this->sn]);
            }
        } catch (\Exception $e) {
            Log::error('CompleteOnuActivationJob: error en enable_tr069', [
                'sn' => $this->sn,
                'error' => $e->getMessage(),
            ]);
        }

        // Paso 3: Configurar modo WAN DHCP con TR069
        try {
            $response = $apiManager->setOnuWanModeDhcp($this->sn);
            $data = $response->json();
            if (($data['status'] ?? false) !== true) {
                Log::warning('CompleteOnuActivationJob: set_onu_wan_mode_dhcp falló', [
                    'sn' => $this->sn,
                    'response' => $data,
                ]);
            } else {
                Log::info('CompleteOnuActivationJob: set_onu_wan_mode_dhcp OK', ['sn' => $this->sn]);
            }
        } catch (\Exception $e) {
            Log::error('CompleteOnuActivationJob: error en set_onu_wan_mode_dhcp', [
                'sn' => $this->sn,
                'error' => $e->getMessage(),
            ]);
        }

        Log::info("CompleteOnuActivationJob: completado para SN: {$this->sn}");
    }
}
