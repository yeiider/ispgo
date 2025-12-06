<?php

namespace App\GraphQL\Mutations;

use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class SmartOltMutation
{
    protected ApiManager $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    public function authorizeOnu($root, array $args)
    {
        try {
            $response = $this->apiManager->authorizeOnu($args);
            $data = $response->json();
            
            if ($data['status'] === true) {
                 return [
                    'success' => true,
                    'message' => 'ONU authorized successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? 'Failed to authorize ONU'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function rebootOnu($root, array $args)
    {
        try {
            $response = $this->apiManager->rebootOnuByExternalId($args['external_id']);
            $data = $response->json();

             if ($data['status'] === true) {
                 return [
                    'success' => true,
                    'message' => 'ONU rebooted successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? 'Failed to reboot ONU'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function enableOnu($root, array $args)
    {
        try {
            $response = $this->apiManager->enableOnu($args['sn']);
            $data = $response->json();

             if ($data['status'] === true) {
                 return [
                    'success' => true,
                    'message' => 'ONU enabled successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? 'Failed to enable ONU'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function disableOnu($root, array $args)
    {
        try {
            $response = $this->apiManager->disableOnu($args['sn']);
            $data = $response->json();

             if ($data['status'] === true) {
                 return [
                    'success' => true,
                    'message' => 'ONU disabled successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? 'Failed to disable ONU'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
