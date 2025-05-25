<?php

namespace Ispgo\Smartolt\Http\Controllers;

use App\Models\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Http\JsonResponse;

class OltController extends Controller
{
    protected ApiManager $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * Get all OLTs from SmartOLT.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOlts(Request $request): JsonResponse
    {
        try {
            $response = $this->apiManager->getOlts();
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get OLT cards details by OLT ID.
     *
     * @param Request $request
     * @param int $oltId
     * @return JsonResponse
     */
    public function getOltCardsDetails(Request $request, int $oltId): JsonResponse
    {
        try {
            $response = $this->apiManager->getOltCardsDetails($oltId);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get unconfigured ONUs for an OLT by OLT ID.
     *
     * @param Request $request
     * @param int $oltId
     * @return JsonResponse
     */
    public function getUnconfiguredOnusForOlt(Request $request, int $oltId): JsonResponse
    {
        try {
            $response = $this->apiManager->getUnconfiguredOnusForOlt($oltId);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for services by customer name.
     *
     * @param Request $request The HTTP request object
     * @param string $query The customer name to search for
     * @return JsonResponse Returns services found or error message
     */
    public function getServices(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            $services = Service::findByCustomerName($query);

            if ($services->isEmpty()) {
                return response()->json([
                    'message' => 'No services found for the given customer name',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'message' => 'Services found successfully',
                'data' => $this->prepareDataFormServices($services)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error searching for services',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    private function prepareDataFormServices($services): array
    {
        $data = [];
        foreach ($services as $service) {
            $data[] = [
                "id" => $service->id,
                "name" => $service->full_service_name,
            ];
        }
        return $data;
    }

    /**
     * Get all zones from SmartOLT.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getZones(Request $request): JsonResponse
    {
        try {
            $response = $this->apiManager->getZones();
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get VLANs by OLT ID from SmartOLT.
     *
     * @param Request $request
     * @param int $oltId
     * @return JsonResponse
     */
    public function getVlansByOltId(Request $request, int $oltId): JsonResponse
    {
        try {
            $response = $this->apiManager->getVlansByOltId($oltId);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
