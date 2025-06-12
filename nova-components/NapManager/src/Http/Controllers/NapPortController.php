<?php

namespace Ispgo\NapManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Ispgo\NapManager\Models\NapPort;

class NapPortController extends Controller
{
    /**
     * Get a specific port.
     *
     * @param int $id The ID of the port to retrieve.
     * @return JsonResponse A JSON response containing the port data.
     */
    public function show($id): JsonResponse
    {
        $port = NapPort::findOrFail($id);
        return response()->json($port);
    }

    /**
     * Create a new port.
     *
     * @param Request $request The incoming HTTP request containing port data.
     * @return JsonResponse A JSON response containing the created port.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nap_box_id' => 'required|exists:nap_boxes,id',
            'port_number' => 'required|integer|min:1',
            'port_name' => 'nullable|string|max:255',
            'status' => 'required|in:available,occupied,damaged,maintenance,reserved,testing',
            'connection_type' => 'required|in:fiber,coaxial,ethernet,mixed',
            'technician_notes' => 'nullable|string'
        ]);

        // Check if port number is already used in this NAP box
        if (!NapPort::validatePortNumber($validated['nap_box_id'], $validated['port_number'])) {
            return response()->json([
                'message' => 'Port number already exists in this NAP box',
                'errors' => ['port_number' => ['Port number already exists in this NAP box']]
            ], 422);
        }

        $port = NapPort::create($validated);
        return response()->json($port, 201);
    }

    /**
     * Update an existing port.
     *
     * @param Request $request The incoming HTTP request containing updated port data.
     * @param int $id The ID of the port to update.
     * @return JsonResponse A JSON response containing the updated port.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $port = NapPort::findOrFail($id);

        $validated = $request->validate([
            'port_number' => 'required|integer|min:1',
            'port_name' => 'nullable|string|max:255',
            'status' => 'required|in:available,occupied,damaged,maintenance,reserved,testing',
            'connection_type' => 'required|in:fiber,coaxial,ethernet,mixed',
            'technician_notes' => 'nullable|string'
        ]);

        // Check if port number is already used in this NAP box (excluding this port)
        if (!NapPort::validatePortNumber($port->nap_box_id, $validated['port_number'], $id)) {
            return response()->json([
                'message' => 'Port number already exists in this NAP box',
                'errors' => ['port_number' => ['Port number already exists in this NAP box']]
            ], 422);
        }

        $port->update($validated);
        return response()->json($port);
    }

    /**
     * Delete a port.
     *
     * @param int $id The ID of the port to delete.
     * @return JsonResponse A JSON response indicating success.
     */
    public function destroy($id): JsonResponse
    {
        $port = NapPort::findOrFail($id);
        $port->delete();
        return response()->json(['message' => 'Port deleted successfully']);
    }
}
