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

    public function removeEquipment($root, array $args)
    {
        try {
            // Buscar el servicio por SN
            $service = \App\Models\Services\Service::where('sn', $args['sn'])->first();

            if (!$service) {
                return [
                    'success' => false,
                    'message' => 'Service with SN ' . $args['sn'] . ' not found'
                ];
            }

            // Obtener el external_id (que es el mismo SN)
            $externalId = $args['sn'];

            // Eliminar la ONU del SmartOLT
            try {
                $response = $this->apiManager->deleteOnuByExternalId($externalId);
                $data = $response->json();

                if ($data['status'] !== true) {
                    Log::warning('Failed to delete ONU from SmartOLT', [
                        'sn' => $args['sn'],
                        'error' => $data['error'] ?? 'Unknown error'
                    ]);
                    // Continuar con la eliminación del SN aunque falle en SmartOLT
                }
            } catch (\Exception $e) {
                Log::error('Error deleting ONU from SmartOLT', [
                    'sn' => $args['sn'],
                    'error' => $e->getMessage()
                ]);
                // Continuar con la eliminación del SN aunque falle en SmartOLT
            }

            // Remover el SN del servicio
            $service->sn = null;
            $service->save();

            return [
                'success' => true,
                'message' => 'Equipment removed successfully from service and SmartOLT'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
