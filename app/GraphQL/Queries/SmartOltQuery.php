<?php

namespace App\GraphQL\Queries;

use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class SmartOltQuery
{
    protected ApiManager $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    public function getOlts($root, array $args)
    {
        $response = $this->apiManager->getOlts();
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getOltCards($root, array $args)
    {
        $response = $this->apiManager->getOltCardsDetails($args['olt_id']);
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getOltPonPorts($root, array $args)
    {
        $response = $this->apiManager->getOltPonPortsDetails($args['olt_id']);
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getZones($root, array $args)
    {
        $response = $this->apiManager->getZones();
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getOdbs($root, array $args)
    {
        $response = $this->apiManager->getOdbs($args['zone_id']);
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getSpeedProfiles($root, array $args)
    {
        $response = $this->apiManager->getSpeedProfiles();
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getUnconfiguredOnus($root, array $args)
    {
        $response = $this->apiManager->getUnconfiguredOnusForOlt($args['olt_id']);
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getOltsUptime($root, array $args)
    {
        $response = $this->apiManager->getOltsUptimeAndEnvTemperature();
        $data = $response->json();
        return $data['response'] ?? [];
    }

    public function getOnuDetails($root, array $args)
    {
        $response = $this->apiManager->getOnuDetailsByExternalId($args['sn']);
        $data = $response->json();

        if (isset($data['onu_details'])) {
            return $data['onu_details'];
        }

        return null;
    }

    public function getOnuTypeImage($root, array $args)
    {
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
    }

    public function getOnuTrafficGraph($root, array $args)
    {
        $graphType = $args['graph_type'] ?? 'hourly';

        Log::info('SmartOLT Traffic Graph Request', [
            'external_id' => $args['external_id'],
            'graph_type' => $graphType
        ]);

        try {
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
}
