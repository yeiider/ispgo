<?php

namespace Ispgo\Smartolt\Http\Controllers;

use App\Models\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Http\JsonResponse;
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

            // Assuming the service has a field for the ONU serial number
            $externalId = $service->sn ?? $service->id;

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
            $externalId = $service->sn ?? $service->id;

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
                'interfaces' => []
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
            $externalId = $service->sn ?? $service->id;

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
     * @return \Illuminate\Http\Response
     */
    public function getSignalGraph(Request $request, $serviceId)
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $service->sn ?? $service->id;

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
     * @return \Illuminate\Http\Response
     */
    public function getTrafficGraph(Request $request, $serviceId, string $graphType = 'hourly')
    {
        try {
            $serviceId = (int)$serviceId;
            $service = Service::findOrFail($serviceId);
            $externalId = $service->sn ?? $service->id;

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
            $externalId = $service->sn ?? $service->id;

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
            $externalId = $service->sn ?? $service->id;

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

            // Assuming the service has a field for the ONU serial number
            $sn = $service->sn;

            if (empty($sn)) {
                return response()->json(['message' => 'ONU serial number not found for this service'], 404);
            }

            $response = $this->apiManager->enableOnu($sn);

            return response()->json(['message' => 'ONU enabled successfully']);
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

            // Assuming the service has a field for the ONU serial number
            $sn = $service->sn;

            if (empty($sn)) {
                return response()->json(['message' => 'ONU serial number not found for this service'], 404);
            }

            $response = $this->apiManager->disableOnu($sn);

            return response()->json(['message' => 'ONU disabled successfully']);
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
            $externalId = $service->sn ?? $service->id;

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
            $externalId = $service->sn ?? $service->id;

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
            $externalId = $service->sn ?? $service->id;

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
}
