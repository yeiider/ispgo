<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class SmartOltController extends Controller
{
    protected ApiManager $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * Get ONU traffic graph by external_id.
     *
     * @param Request $request
     * @param string $externalId
     * @param string|null $graphType
     * @return Response
     */
    public function getTrafficGraph(Request $request, string $externalId, ?string $graphType = null): Response
    {
        try {
            $graphType = $graphType ?? $request->get('graph_type', 'hourly');

            Log::info('SmartOLT API - Traffic Graph Request', [
                'external_id' => $externalId,
                'graph_type' => $graphType,
                'ip' => $request->ip()
            ]);

            $response = $this->apiManager->getOnuTrafficGraphByExternalId($externalId, $graphType);

            if ($response->successful() && !empty($response->body())) {
                Log::info('SmartOLT API - Traffic Graph Success', [
                    'external_id' => $externalId,
                    'body_length' => strlen($response->body())
                ]);

                // Retornar imagen binaria directa
                return response($response->body(), 200, [
                    'Content-Type' => 'image/png',
                    'Cache-Control' => 'public, max-age=300', // Cache 5 minutos
                ]);
            }

            Log::warning('SmartOLT API - Traffic Graph Empty Response', [
                'external_id' => $externalId,
                'status' => $response->status()
            ]);

            // Retornar error 404 si no hay imagen
            return response()->json([
                'error' => 'Traffic graph not found',
                'message' => 'No traffic graph available for this ONU'
            ], 404);

        } catch (\Exception $e) {
            Log::error('SmartOLT API - Traffic Graph Error', [
                'external_id' => $externalId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ONU signal graph by external_id.
     *
     * @param Request $request
     * @param string $externalId
     * @return Response
     */
    public function getSignalGraph(Request $request, string $externalId): Response
    {
        try {
            Log::info('SmartOLT API - Signal Graph Request', [
                'external_id' => $externalId,
                'ip' => $request->ip()
            ]);

            $response = $this->apiManager->getOnuSignalGraphByExternalId($externalId);

            if ($response->successful() && !empty($response->body())) {
                Log::info('SmartOLT API - Signal Graph Success', [
                    'external_id' => $externalId,
                    'body_length' => strlen($response->body())
                ]);

                // Retornar imagen binaria directa
                return response($response->body(), 200, [
                    'Content-Type' => 'image/png',
                    'Cache-Control' => 'public, max-age=300', // Cache 5 minutos
                ]);
            }

            Log::warning('SmartOLT API - Signal Graph Empty Response', [
                'external_id' => $externalId,
                'status' => $response->status()
            ]);

            // Retornar error 404 si no hay imagen
            return response()->json([
                'error' => 'Signal graph not found',
                'message' => 'No signal graph available for this ONU'
            ], 404);

        } catch (\Exception $e) {
            Log::error('SmartOLT API - Signal Graph Error', [
                'external_id' => $externalId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ONU details by external_id.
     *
     * @param Request $request
     * @param string $externalId
     * @return Response
     */
    public function getOnuDetails(Request $request, string $externalId): Response
    {
        try {
            $response = $this->apiManager->getOnuDetailsByExternalId($externalId);
            $data = $response->json();

            if ($response->successful() && isset($data['onu_details'])) {
                return response()->json($data['onu_details'], 200);
            }

            return response()->json([
                'error' => 'ONU not found',
                'message' => 'No details available for this ONU'
            ], 404);

        } catch (\Exception $e) {
            Log::error('SmartOLT API - ONU Details Error', [
                'external_id' => $externalId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ONU full status by external_id.
     *
     * @param Request $request
     * @param string $externalId
     * @return Response
     */
    public function getOnuStatus(Request $request, string $externalId): Response
    {
        try {
            $response = $this->apiManager->getOnuFullStatusByExternalId($externalId);
            $data = $response->json();

            if ($response->successful()) {
                return response()->json($data, 200);
            }

            return response()->json([
                'error' => 'ONU status not available',
                'message' => 'Could not retrieve ONU status'
            ], 404);

        } catch (\Exception $e) {
            Log::error('SmartOLT API - ONU Status Error', [
                'external_id' => $externalId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
