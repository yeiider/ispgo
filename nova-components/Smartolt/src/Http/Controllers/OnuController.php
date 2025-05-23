<?php

namespace Ispgo\Smartolt\Http\Controllers;

use App\Models\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Http\JsonResponse;

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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function getDetails(Request $request, int $serviceId): JsonResponse
    {
        try {
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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function getConfig(Request $request, int $serviceId): JsonResponse
    {
        try {
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
     * @param int $serviceId
     * @return \Illuminate\Http\Response
     */
    public function getSignalGraph(Request $request, int $serviceId)
    {
        try {
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
     * @param int $serviceId
     * @param string $graphType
     * @return \Illuminate\Http\Response
     */
    public function getTrafficGraph(Request $request, int $serviceId, string $graphType = 'hourly')
    {
        try {
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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function reboot(Request $request, int $serviceId): JsonResponse
    {
        try {
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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function factoryReset(Request $request, int $serviceId): JsonResponse
    {
        try {
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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function enable(Request $request, int $serviceId): JsonResponse
    {
        try {
            $service = Service::findOrFail($serviceId);

            // Assuming the service has a field for the ONU serial number
            $sn = $service->onu_sn;

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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function disable(Request $request, int $serviceId): JsonResponse
    {
        try {
            $service = Service::findOrFail($serviceId);

            // Assuming the service has a field for the ONU serial number
            $sn = $service->onu_sn;

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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function updateSpeedProfile(Request $request, int $serviceId): JsonResponse
    {
        try {
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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function updateVlan(Request $request, int $serviceId): JsonResponse
    {
        try {
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
     * @param int $serviceId
     * @return JsonResponse
     */
    public function updateWanMode(Request $request, int $serviceId): JsonResponse
    {
        try {
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
}
