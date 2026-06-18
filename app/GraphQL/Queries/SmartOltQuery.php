<?php

namespace App\GraphQL\Queries;

use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class SmartOltQuery
{
    protected ApiManager $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    private function isConfigured(): bool
    {
        return ProviderSmartOlt::getEnabled() && !empty(ProviderSmartOlt::getUrl()) && !empty(ProviderSmartOlt::getToken());
    }

    public function getOlts($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getOlts();
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getOlts error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOltCards($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getOltCardsDetails($args['olt_id']);
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getOltCards error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOltPonPorts($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getOltPonPortsDetails($args['olt_id']);
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getOltPonPorts error: ' . $e->getMessage());
            return [];
        }
    }

    public function getZones($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getZones();
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getZones error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOdbs($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getOdbs($args['zone_id']);
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getOdbs error: ' . $e->getMessage());
            return [];
        }
    }

    public function getSpeedProfiles($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getSpeedProfiles();
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getSpeedProfiles error: ' . $e->getMessage());
            return [];
        }
    }

    public function getUnconfiguredOnus($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getUnconfiguredOnusForOlt($args['olt_id']);
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getUnconfiguredOnus error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOltsUptime($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getOltsUptimeAndEnvTemperature();
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getOltsUptime error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOnuDetails($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return null;
            }
            $response = $this->apiManager->getOnuDetailsByExternalId($args['sn']);
            $data = $response->json();

            if (isset($data['onu_details'])) {
                return $data['onu_details'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('SmartOLT getOnuDetails error: ' . $e->getMessage());
            return null;
        }
    }

    public function getVlans($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [];
            }
            $response = $this->apiManager->getVlansByOltId((int) $args['olt_id']);
            $data = $response->json();
            return $data['response'] ?? [];
        } catch (\Exception $e) {
            Log::error('SmartOLT getVlans error: ' . $e->getMessage());
            return [];
        }
    }

    public function getInstallationFormData($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return null;
            }
            $sn = $args['sn'];

            $onuResponse = $this->apiManager->getUnconfiguredOnusBySn($sn);
            $onuData = $onuResponse->json();
            $onu = !empty($onuData['response']) ? $onuData['response'][0] : null;

            if (!$onu) {
                return null;
            }

            $oltId = (int) $onu['olt_id'];

            $vlanData     = $this->apiManager->getVlansByOltId($oltId)->json();
            $zonesData    = $this->apiManager->getZones()->json();
            $profilesData = $this->apiManager->getSpeedProfiles()->json();

            return [
                'onu'           => $onu,
                'vlans'         => $vlanData['response'] ?? [],
                'zones'         => $zonesData['response'] ?? [],
                'speed_profiles' => $profilesData['response'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('SmartOLT getInstallationFormData error: ' . $e->getMessage());
            return null;
        }
    }

    public function getOnuTypeImage($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return null;
            }
            $response = $this->apiManager->getOnuTypeImage($args['onu_type_id']);

            // Retornar la imagen como base64
            if ($response->successful()) {
                $imageData = base64_encode($response->body());
                return [
                    'image_base64' => $imageData,
                    'content_type' => $response->header('Content-Type') ?? 'image/jpeg'
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('SmartOLT getOnuTypeImage error: ' . $e->getMessage());
            return null;
        }
    }

    public function getOnuTrafficGraph($root, array $args)
    {
        $graphType = $args['graph_type'] ?? 'hourly';

        Log::info('SmartOLT Traffic Graph Request', [
            'external_id' => $args['external_id'],
            'graph_type' => $graphType
        ]);

        try {
            if (!$this->isConfigured()) {
                return null;
            }
            $response = $this->apiManager->getOnuTrafficGraphByExternalId($args['external_id'], $graphType);

            $body = $response->body();
            $bodyLength = strlen($body);
            $contentType = $response->header('Content-Type') ?? 'image/png';

            Log::info('SmartOLT Traffic Graph Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_length' => $bodyLength,
                'content_type' => $contentType,
                'body_is_empty' => empty($body),
                'body_preview' => $bodyLength > 0 ? substr($body, 0, 100) : 'EMPTY',
                'all_headers' => $response->headers(),
                'transfer_stats' => method_exists($response, 'transferStats') ? $response->transferStats?->getHandlerStats() : null
            ]);

            // La API retorna binario directo de la imagen
            if ($response->successful() && !empty($body)) {
                $imageData = base64_encode($body);

                Log::info('SmartOLT Traffic Graph: Image encoded successfully', [
                    'base64_length' => strlen($imageData),
                    'base64_preview' => substr($imageData, 0, 50) . '...'
                ]);

                return [
                    'image_base64' => $imageData,
                    'content_type' => $contentType
                ];
            }

            Log::warning('SmartOLT Traffic Graph: Empty or failed response', [
                'status' => $response->status(),
                'body_length' => $bodyLength
            ]);

            return [
                'image_base64' => '',
                'content_type' => 'image/png'
            ];

        } catch (\Exception $e) {
            Log::error('SmartOLT Traffic Graph: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    public function getCatvStatus($root, array $args)
    {
        try {
            if (!$this->isConfigured()) {
                return [
                    'enabled' => false,
                    'message' => 'SmartOLT is not configured'
                ];
            }
            $response = $this->apiManager->getOnuCatvStatusByExternalId($args['external_id']);
            $data = $response->json();

            $enabled = $data['status'] === true && ($data['catv_enabled'] ?? $data['enabled'] ?? false);

            return [
                'enabled' => (bool) $enabled,
                'message' => $data['message'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('SmartOLT CATV Status error', [
                'external_id' => $args['external_id'],
                'error' => $e->getMessage()
            ]);

            return [
                'enabled' => false,
                'message' => 'Error fetching CATV status: ' . $e->getMessage()
            ];
        }
    }
}
