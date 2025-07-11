<?php

namespace Ispgo\NapManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Ispgo\NapManager\Models\NapBox;
use Ispgo\NapManager\Models\NapPort;

class NapBoxController extends Controller
{
    /**
     * Get a specific NAP box with its ports.
     *
     * @param int $id The ID of the NAP box to retrieve.
     * @return JsonResponse A JSON response containing the NAP box data.
     */
    public function show($id): JsonResponse
    {
        $napBox = NapBox::with('ports')->findOrFail($id);
        return response()->json($napBox);
    }

    /**
     * Create a new NAP box.
     *
     * @param Request $request The incoming HTTP request containing NAP box data.
     * @return JsonResponse A JSON response containing the created NAP box.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:nap_boxes,code',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance,damaged',
            'capacity' => 'required|integer|min:1',
            'technology_type' => 'required|in:fiber,coaxial,ftth,mixed',
            'installation_date' => 'required|date',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'parent_nap_id' => 'nullable|exists:nap_boxes,id'
        ]);

        $napBox = NapBox::create($validated);
        return response()->json($napBox, 201);
    }

    /**
     * Update an existing NAP box.
     *
     * @param Request $request The incoming HTTP request containing updated NAP box data.
     * @param int $id The ID of the NAP box to update.
     * @return JsonResponse A JSON response containing the updated NAP box.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $napBox = NapBox::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:nap_boxes,code,' . $id,
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance,damaged',
            'capacity' => 'required|integer|min:1',
            'technology_type' => 'required|in:fiber,coaxial,ftth,mixed',
            'installation_date' => 'required|date',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'parent_nap_id' => 'nullable|exists:nap_boxes,id'
        ]);

        $napBox->update($validated);
        return response()->json($napBox);
    }

    /**
     * Delete a NAP box.
     *
     * @param int $id The ID of the NAP box to delete.
     * @return JsonResponse A JSON response indicating success.
     */
    public function destroy($id): JsonResponse
    {
        $napBox = NapBox::findOrFail($id);
        $napBox->delete();
        return response()->json(['message' => 'NAP box deleted successfully']);
    }
}
