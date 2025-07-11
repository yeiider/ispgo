<?php

namespace Ispgo\NapManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Ispgo\NapManager\Models\NapBox;
use Ispgo\NapManager\Models\NapDistribution;

class NapMapController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getMapData(Request $request)
    {
        $napBoxes = NapBox::with(['ports'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get()
            ->map(function ($nap) {
                return $nap->getMapMarkerData();
            });

        return response()->json($napBoxes);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getFlowData(Request $request): JsonResponse
    {
        $nodes = [];
        $edges = [];
        $processedEdges = []; // To track edges we've already added

        $napBoxes = NapBox::with(['distributionFlow', 'childNaps'])->get();

        foreach ($napBoxes as $nap) {
            // If the NAP box has a distribution flow, use that data
            if ($nap->distributionFlow) {
                $nodes[] = $nap->distributionFlow->getFlowNodeData();

                // Create edges based on connection_data if available
                if (!empty($nap->distributionFlow->connection_data) && is_array($nap->distributionFlow->connection_data)) {
                    foreach ($nap->distributionFlow->connection_data as $targetId) {
                        $edgeId = "edge-{$nap->id}-{$targetId}";

                        // Only add if we haven't processed this edge yet
                        if (!in_array($edgeId, $processedEdges)) {
                            $edges[] = [
                                'id' => $edgeId,
                                'source' => (string)$nap->id,
                                'target' => (string)$targetId,
                                'type' => 'smoothstep',
                                'animated' => true
                            ];
                            $processedEdges[] = $edgeId;
                        }
                    }
                }
            } else {
                // If not, create a default node for the NAP box
                $nodes[] = [
                    'id' => (string)$nap->id,
                    'type' => 'default', // Use default node type instead of custom napNode
                    'position' => [
                        'x' => rand(0, 500), // Random initial position
                        'y' => rand(0, 300)
                    ],
                    'data' => [
                        'label' => $nap->name,
                        'code' => $nap->code,
                        'status' => $nap->status,
                        'occupancy' => $nap->getOccupancyPercentage(),
                        'level' => 1 // Default level
                    ]
                ];
            }

            // Crear conexiones/edges basadas en parent-child relationships
            foreach ($nap->childNaps as $childNap) {
                $edgeId = "edge-{$nap->id}-{$childNap->id}";

                // Only add if we haven't processed this edge yet
                if (!in_array($edgeId, $processedEdges)) {
                    $edges[] = [
                        'id' => $edgeId,
                        'source' => (string)$nap->id,
                        'target' => (string)$childNap->id,
                        'type' => 'smoothstep',
                        'animated' => true
                    ];
                    $processedEdges[] = $edgeId;
                }
            }
        }

        return response()->json([
            'nodes' => $nodes,
            'edges' => $edges
        ]);

    }

    /**
     * @param Request $request
     * @param $napBoxId
     * @return JsonResponse
     */
    public function updateNodePosition(Request $request, $napBoxId): JsonResponse
    {
        $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric'
        ]);

        $distribution = NapDistribution::firstOrCreate(
            ['nap_box_id' => $napBoxId],
            [
                'flow_position_x' => $request->x,
                'flow_position_y' => $request->y,
                'flow_level' => 1,
                'connection_data' => json_encode([])
            ]
        );

        $distribution->update([
            'flow_position_x' => $request->x,
            'flow_position_y' => $request->y
        ]);

        return response()->json(['success' => true]);
    }


    /**
     * Updates the parent-child relationship between two entities.
     *
     * This method retrieves a target entity by its ID and updates its parent
     * relationship with the provided source entity ID. The operation expects
     * both 'source' and 'target' fields to be present in the request.
     *
     * @param Request $request The incoming HTTP request containing 'source' and 'target' parameters.
     * @return JsonResponse A JSON response indicating whether the operation was successful.
     */
    public function updateConnection(Request $request): JsonResponse
    {
        $request->validate([
            'source' => 'required',
            'target' => 'required'
        ]);

        // Actualizar la relación parent-child
        $childNap = NapBox::find($request->target);
        if ($childNap) {
            $childNap->update(['parent_nap_id' => $request->source]);

            // Update connection_data in NapDistribution for both source and target
            $sourceId = $request->source;
            $targetId = $request->target;

            // For source node
            $sourceDistribution = NapDistribution::firstOrCreate(
                ['nap_box_id' => $sourceId],
                [
                    'flow_position_x' => rand(0, 500),
                    'flow_position_y' => rand(0, 300),
                    'flow_level' => 1,
                    'connection_data' => json_encode([])
                ]
            );

            // Get current connections or initialize empty array
            $sourceConnections = $sourceDistribution->connection_data ?? [];
            if (!is_array($sourceConnections)) {
                $sourceConnections = [];
            }

            // Add target to source connections if not already present
            if (!in_array($targetId, $sourceConnections)) {
                $sourceConnections[] = $targetId;
                $sourceDistribution->update(['connection_data' => $sourceConnections]);
            }

            // For target node
            $targetDistribution = NapDistribution::firstOrCreate(
                ['nap_box_id' => $targetId],
                [
                    'flow_position_x' => rand(0, 500),
                    'flow_position_y' => rand(0, 300),
                    'flow_level' => 1,
                    'connection_data' => json_encode([])
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}
