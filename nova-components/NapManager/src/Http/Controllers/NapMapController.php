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

        $napBoxes = NapBox::with(['distributionFlow', 'childNaps'])->get();

        foreach ($napBoxes as $nap) {
            if ($nap->distributionFlow) {
                $nodes[] = $nap->distributionFlow->getFlowNodeData();
            }

            // Crear conexiones/edges basadas en parent-child relationships
            foreach ($nap->childNaps as $childNap) {
                $edges[] = [
                    'id' => "edge-{$nap->id}-{$childNap->id}",
                    'source' => (string)$nap->id,
                    'target' => (string)$childNap->id,
                    'type' => 'smoothstep'
                ];
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
                'flow_level' => 1
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
        }

        return response()->json(['success' => true]);
    }
}
