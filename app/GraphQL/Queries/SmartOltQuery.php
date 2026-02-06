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

        $response = $this->apiManager->getOnuTrafficGraphByExternalId($args['external_id'], $graphType);

        Log::info('SmartOLT Traffic Graph Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body_length' => strlen($response->body()),
            'content_type' => $response->header('Content-Type')
        ]);

        // Retornar la imagen como base64
        if ($response->successful()) {
            $body = $response->body();

            // Verificar si el body no está vacío
            if (empty($body)) {
                Log::warning('SmartOLT Traffic Graph: Empty response body');
                return [
                    'image_base64' => '',
                    'content_type' => 'image/png'
                ];
            }

            $imageData = base64_encode($body);
            return [
                'image_base64' => $imageData,
                'content_type' => $response->header('Content-Type') ?? 'image/png'
            ];
        }

        Log::error('SmartOLT Traffic Graph: Request failed', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return null;
    }
}
