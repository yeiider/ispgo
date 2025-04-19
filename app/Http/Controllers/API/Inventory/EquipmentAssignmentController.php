<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\EquipmentAssignmentRequest;
use App\Http\Resources\Inventory\EquipmentAssignmentResource;
use App\Services\Inventory\EquipmentAssignmentService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="EquipmentAssignments",
 *     description="API Endpoints for managing equipment assignments"
 * )
 *
 * @OA\Schema(
 *     schema="EquipmentAssignmentRequest",
 *     type="object",
 *     title="Equipment Assignment Request Schema",
 *     description="Schema for creating or updating equipment assignments",
 *     required={"user_id", "product_id", "assigned_at"},
 *     @OA\Property(property="user_id", type="integer", description="ID of the user the equipment is assigned to", example=1),
 *     @OA\Property(property="product_id", type="integer", description="ID of the assigned product", example=101),
 *     @OA\Property(property="assigned_at", type="string", format="date", description="Date the equipment was assigned", example="2023-11-19"),
 *     @OA\Property(property="returned_at", type="string", format="date", description="Date the equipment was returned, if applicable", example="2023-12-01"),
 *     @OA\Property(property="status", type="string", description="Current status of the assignment (e.g., 'Active', 'Returned')", example="Active"),
 *     @OA\Property(property="condition_on_assignment", type="string", description="Condition of the equipment when assigned", example="New"),
 *     @OA\Property(property="condition_on_return", type="string", description="Condition of the equipment when returned", example="Slightly Used"),
 *     @OA\Property(property="notes", type="string", description="Additional notes or comments", example="Handle with care"),
 * )
 *
 * @OA\Schema(
 *     schema="EquipmentAssignmentResource",
 *     type="object",
 *     title="Equipment Assignment Resource Schema",
 *     description="Representation of an equipment assignment",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the assignment", example=1),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user the equipment is assigned to", example=1),
 *     @OA\Property(property="product_id", type="integer", description="ID of the assigned product", example=101),
 *     @OA\Property(property="assigned_at", type="string", format="date", description="Date the equipment was assigned", example="2023-11-19"),
 *     @OA\Property(property="returned_at", type="string", format="date", description="Date the equipment was returned, if applicable", example="2023-12-01"),
 *     @OA\Property(property="status", type="string", description="Current status of the assignment (e.g., 'Active', 'Returned')", example="Active"),
 *     @OA\Property(property="condition_on_assignment", type="string", description="Condition of the equipment when assigned", example="New"),
 *     @OA\Property(property="condition_on_return", type="string", description="Condition of the equipment when returned", example="Slightly Used"),
 *     @OA\Property(property="notes", type="string", description="Additional notes or comments", example="Handle with care"),
 * )
 */
class EquipmentAssignmentController extends Controller
{
    /**
     * @var EquipmentAssignmentService
     */
    protected EquipmentAssignmentService $equipmentAssignmentService;

    /**
     * Constructor.
     *
     * @param EquipmentAssignmentService $equipmentAssignmentService
     */
    public function __construct(EquipmentAssignmentService $equipmentAssignmentService)
    {
        $this->equipmentAssignmentService = $equipmentAssignmentService;
    }

    /**
     * Retrieve all equipment assignments.
     *
     * @OA\Get(
     *     path="/api/equipment-assignments",
     *     summary="List all equipment assignments",
     *     tags={"EquipmentAssignments"},
     *     @OA\Response(
     *         response=200,
     *         description="List of equipment assignments retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/EquipmentAssignmentResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index()
    {
        return EquipmentAssignmentResource::collection($this->equipmentAssignmentService->getAll());
    }

    /**
     * Create a new equipment assignment.
     *
     * @OA\Post(
     *     path="/api/equipment-assignments",
     *     summary="Create a new equipment assignment",
     *     tags={"EquipmentAssignments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EquipmentAssignmentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Equipment assignment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EquipmentAssignmentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(EquipmentAssignmentRequest $request)
    {
        try {
            return new EquipmentAssignmentResource($this->equipmentAssignmentService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific equipment assignment by ID.
     *
     * @OA\Get(
     *     path="/api/equipment-assignments/{id}",
     *     summary="Get an equipment assignment by ID",
     *     tags={"EquipmentAssignments"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Equipment Assignment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Equipment assignment retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EquipmentAssignmentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Equipment assignment not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return EquipmentAssignmentResource::make($this->equipmentAssignmentService->getById($id));
    }

    /**
     * Update an existing equipment assignment.
     *
     * @OA\Put(
     *     path="/api/equipment-assignments/{id}",
     *     summary="Update an equipment assignment",
     *     tags={"EquipmentAssignments"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Equipment Assignment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EquipmentAssignmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Equipment assignment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EquipmentAssignmentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Equipment assignment not found"
     *     )
     * )
     */
    public function update(EquipmentAssignmentRequest $request, int $id)
    {
        try {
            return new EquipmentAssignmentResource($this->equipmentAssignmentService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an equipment assignment.
     *
     * @OA\Delete(
     *     path="/api/equipment-assignments/{id}",
     *     summary="Delete an equipment assignment",
     *     tags={"EquipmentAssignments"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Equipment Assignment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Equipment assignment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Equipment assignment not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->equipmentAssignmentService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
