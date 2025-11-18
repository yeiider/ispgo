<?php

namespace Ispgo\Smartolt\Http\Controllers;

use App\Models\Services\Service;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OnuController extends Controller
{
    protected ApiManager $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * Get ONU details for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function getDetails(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $response = $this->apiManager->getOnuDetailsByExternalId($externalId);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get ONU status for a service.
     *
     * @param Request $request
     * @param int $serviceId
     * @return JsonResponse
     */
    public function getStatus(Request $request, int $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $response = $this->apiManager->getOnuFullStatusByExternalId($externalId);
            $responseData = $response->json();

            // Extract and organize data from the full status response
            $data = [
                'status' => $responseData['status'] ?? false,
                'response_code' => $responseData['response_code'] ?? '',
                'details' => [],
                'optical' => [],
                'config' => [],
                'history' => [],
                'interfaces' => [],
                'catv' => [
                    'status' => null,
                    'value' => null,
                    'error' => null,
                ],
            ];

            if (isset($responseData['full_status_json'])) {
                // Extract ONU details
                if (isset($responseData['full_status_json']['ONU details'])) {
                    $data['details'] = $responseData['full_status_json']['ONU details'];
                }

                // Extract optical status
                if (isset($responseData['full_status_json']['Optical status'])) {
                    $data['optical'] = $responseData['full_status_json']['Optical status'];
                }

                // Extract configuration data
                if (isset($responseData['full_status_json']['ONU LAN Interfaces status'])) {
                    $data['config']['interfaces'] = $responseData['full_status_json']['ONU LAN Interfaces status'];
                }
                if (isset($responseData['full_status_json']['Realtime VLAN info'])) {
                    $data['config']['vlan'] = $responseData['full_status_json']['Realtime VLAN info'];
                }

                // Extract history data
                if (isset($responseData['full_status_json']['History'])) {
                    $data['history'] = $responseData['full_status_json']['History'];
                }

                // Extract interfaces data
                if (isset($responseData['full_status_json']['MACs on OLT from this ONU'])) {
                    $data['interfaces']['macs'] = $responseData['full_status_json']['MACs on OLT from this ONU'];
                }
            }

            try {
                $catvResponse = $this->apiManager->getOnuCatvStatusByExternalId($externalId);
                $catvData = $catvResponse->json();
                $data['catv'] = [
                    'status' => $catvData['status'] ?? $catvResponse->successful(),
                    'value' => $catvData['onu_catv_status'] ?? ($catvData['response'] ?? null),
                    'error' => null,
                    'raw' => $catvData,
                ];
            } catch (\Exception $catvException) {
                Log::warning("No fue posible obtener el estado CATV para el servicio {$service->id}: {$catvException->getMessage()}");
                $data['catv']['status'] = false;
                $data['catv']['value'] = null;
                $data['catv']['error'] = $catvException->getMessage();
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get ONU configuration for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function getConfig(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $response = $this->apiManager->getOnuRunningConfigByExternalId($externalId);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get ONU signal graph for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return ResponseFactory|Application|object|Response
     */
    public function getSignalGraph(Request $request, $serviceId)
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);
            $response = $this->apiManager->getOnuSignalGraphByExternalId($externalId);

            // Set the content type to image/png as specified in the requirements
            return response($response->body(), 200, ['Content-Type' => 'image/png']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get ONU traffic graph for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @param string $graphType
     * @return ResponseFactory|Application|object|Response
     */
    public function getTrafficGraph(Request $request, $serviceId, string $graphType = 'hourly')
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $response = $this->apiManager->getOnuTrafficGraphByExternalId($externalId, $graphType);

            // Set the content type to image/png as specified in the requirements
            return response($response->body(), 200, ['Content-Type' => 'image/png']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reboot ONU for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function reboot(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $response = $this->apiManager->rebootOnuByExternalId($externalId);

            return response()->json(['message' => 'ONU reboot initiated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Factory reset ONU for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function factoryReset(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $response = $this->apiManager->restoreOnuFactoryDefaultsByExternalId($externalId);

            return response()->json(['message' => 'ONU factory reset initiated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Enable ONU for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function enable(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);
            $sn = $service->sn;

            if (empty($sn)) {
                return response()->json(['message' => 'ONU serial number not found for this service'], 404);
            }

            $response = $this->apiManager->enableOnu($sn);

            $catvResult = $this->triggerCatvAction($externalId, 'enable');

            return response()->json([
                'message' => 'ONU enabled successfully',
                'catv' => $catvResult,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Disable ONU for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function disable(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);
            $sn = $service->sn;

            if (empty($sn)) {
                return response()->json(['message' => 'ONU serial number not found for this service'], 404);
            }

            $response = $this->apiManager->disableOnu($sn);

            $catvResult = $this->triggerCatvAction($externalId, 'disable');

            return response()->json([
                'message' => 'ONU disabled successfully',
                'catv' => $catvResult,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Enable CATV for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function enableCatv(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $catvResult = $this->triggerCatvAction($externalId, 'enable');

            return response()->json([
                'message' => 'CATV enabled successfully',
                'catv' => $catvResult,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Disable CATV for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function disableCatv(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $catvResult = $this->triggerCatvAction($externalId, 'disable');

            return response()->json([
                'message' => 'CATV disabled successfully',
                'catv' => $catvResult,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update ONU speed profile for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function updateSpeedProfile(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $request->validate([
                'speed_profile_id' => 'required|integer',
            ]);

            $response = $this->apiManager->updateOnuSpeedProfileByExternalId($externalId, $request->speed_profile_id);

            return response()->json(['message' => 'ONU speed profile updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update ONU VLAN for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function updateVlan(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $request->validate([
                'vlan_id' => 'required|integer',
            ]);

            $response = $this->apiManager->updateOnuMainVlanByExternalId($externalId, $request->vlan_id);

            return response()->json(['message' => 'ONU VLAN updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update ONU WAN mode for a service.
     *
     * @param Request $request
     * @param string|int $serviceId
     * @return JsonResponse
     */
    public function updateWanMode(Request $request, $serviceId): JsonResponse
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $this->resolveExternalId($service);

            $request->validate([
                'wan_mode' => 'required|string',
            ]);

            $response = $this->apiManager->setOnuWanModeByExternalId($externalId, $request->wan_mode);

            return response()->json(['message' => 'ONU WAN mode updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Authorize ONU for a service.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function authorize(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'onu' => 'required|array',
                'onu.id' => 'required|integer',
                'onu.pon_type' => 'required|string',
                'onu.board' => 'required|integer',
                'onu.port' => 'required|integer',
                'onu.sn' => 'required|string',
                'onu.olt_id' => 'required|string',
                'service_id' => 'required|integer',
                'onu_mode' => 'required|string',
                'zone' => 'required|string',
                'vlan' => 'required|integer',
            ]);

            $service = Service::find($request->input('service_id'));
            $customer = $service->customer;
            $payload = [
                'olt_id' => $request->input('onu.olt_id'),
                'pon_type' => $request->input('onu.pon_type'),
                'board' => $request->input('onu.board'),
                'port' => $request->input('onu.port'),
                'sn' => $request->input('onu.sn'),
                'vlan' => $request->input('vlan'),
                'onu_type' => $request->input('onu.onu_type_name', 'ZTE-F660V6.0'),
                'zone' => $request->input('zone'),
                'name' => $customer->identity_document,
                'address_or_comment' => $customer->addresses()->first()->address ?? 'Unknown',
                'onu_mode' => $request->input('onu_mode'),
                'onu_external_id' => $customer->identity_document,
                'upload_speed_profile_name' => $service->plan->upload_speed . 'M',
                'download_speed_profile_name' => $service->plan->download_speed . 'M',
            ];

            //using dhcp
            //$this->apiManager->authorizeOnu($payload);
            //$this->apiManager->setOnuManagementIpDhcpByExternalId($request->input('onu.sn'), $request->input('vlan'));;
            return response()->json([
                'status' => true,
                'payload' => $payload
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Determina el external_id a utilizar para las peticiones a SmartOLT.
     */
    private function resolveExternalId(Service $service): string
    {
        // if ($service->customer && !empty($service->customer->identity_document)) {
        //     return (string)$service->customer->identity_document;
        // }

        if (!empty($service->sn)) {
            return (string)$service->sn;
        }

        return (string)$service->id;
    }

    /**
     * Ejecuta la acción correspondiente de CATV y retorna el resultado formateado.
     *
     * @param string $externalId
     * @param string $action
     * @return array
     */
    private function triggerCatvAction(string $externalId, string $action): array
    {
        try {
            $response = $action === 'enable'
                ? $this->apiManager->enableOnuCatvByExternalId($externalId)
                : $this->apiManager->disableOnuCatvByExternalId($externalId);

            $payload = $response->json();

            return [
                'status' => $response->successful() && ($payload['status'] ?? true),
                'value' => $payload['onu_catv_status'] ?? ($payload['response'] ?? null),
                'message' => $payload['response'] ?? ($payload['onu_catv_status'] ?? null),
                'raw' => $payload,
            ];
        } catch (\Exception $exception) {
            Log::warning("No fue posible {$action} la CATV para external_id {$externalId}: {$exception->getMessage()}");
            return [
                'status' => false,
                'value' => null,
                'message' => null,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
