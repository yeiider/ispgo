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
        $response = $this->apiManager->getOnuDetailsBySn($args['sn']);
        $data = $response->json();

        if (isset($data['onus']) && count($data['onus']) > 0) {
            return $data['onus'][0];
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
}
