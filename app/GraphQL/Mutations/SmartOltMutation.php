<?php

namespace App\GraphQL\Mutations;

use App\Models\Services\Service;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessOnuAuthorization;

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
            // Obtener el servicio
            $service = Service::with(['customer', 'plan'])->find($args['service_id']);

            if (!$service) {
                return [
                    'success' => false,
                    'message' => 'Service not found with ID: ' . $args['service_id']
                ];
            }

            $customer = $service->customer;

            if (!$customer) {
                return [
                    'success' => false,
                    'message' => 'Customer not found for service ID: ' . $args['service_id']
                ];
            }

            // Construir el payload para la API de SmartOLT
            $payload = [
                'olt_id' => $args['olt_id'],
                'pon_type' => $args['pon_type'],
                'board' => $args['board'],
                'port' => $args['port'],
                'sn' => $args['sn'],
                'vlan' => $args['vlan'],
                'onu_type' => $args['onu_type'],
                'zone' => $args['zone'],
                'onu_mode' => $args['onu_mode'],
                'name' => $args['name'] = strtoupper($this->limpiarCadena($customer->full_name)),
                'address_or_comment' => preg_replace('/[^a-zA-Z0-9]/', ' ', str_replace('ñ', 'n', str_replace('Ñ', 'N', $customer->addresses()->first()->address ?? 'N/A'))),
            ];

            // Agregar odb si está presente
            if (!empty($args['odb'])) {
                $payload['odb'] = $args['odb'];
            }

            Log::info('SmartOLT authorizeOnu payload', ['payload' => $payload]);

            $response = $this->apiManager->authorizeOnu($payload);
            $data = $response->json();

            if ($data['status'] === true) {
                // Guardar el SN en el servicio
                try {
                    $service->sn = $args['sn'];
                    $service->save();
                    $this->apiManager->setOnuManagementIpDhcpByExternalId($args['sn'], $args['vlan']);

                    // Dispatch job to finalize configuration after 3 minutes
                    ProcessOnuAuthorization::dispatch($service->id, $args['sn'], $args['vlan'], $args['olt_id'])
                        ->delay(now()->addMinutes(3))->onQueue('redis');

                } catch (\Exception $e) {
                    Log::error('Failed to set ONU management IP DHCP or dispatch job', ['exception' => $e]);
                }
                return [
                    'success' => true,
                    'message' => 'ONU authorized successfully and assigned to service'
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? 'Failed to authorize ONU'
            ];

        } catch (\Exception $e) {
            Log::error('SmartOLT authorizeOnu error', [
                'message' => $e->getMessage(),
                'args' => $args
            ]);

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

    private function limpiarCadena($str): array|string
    {
        // 1. Definir los caracteres originales y sus reemplazos
        $originales = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'];
        $modificadas = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'd', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'];

        // 2. Reemplazar los caracteres especiales
        $cadena = str_replace($originales, $modificadas, $str);

        return $cadena;
    }
}
