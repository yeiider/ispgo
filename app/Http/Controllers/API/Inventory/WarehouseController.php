<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\WarehouseRequest;
use App\Http\Resources\Inventory\WarehouseResource;
use App\Services\Inventory\WarehouseService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Warehouses",
 *     description="API Endpoints for managing warehouses"
 * )
 *
 * @OA\Schema(
 *     schema="WarehouseRequest",
 *     type="object",
 *     title="Warehouse Request Schema",
 *     description="Schema for creating or updating warehouses",
 *     required={"name", "address", "code"},
 *     @OA\Property(property="name", type="string", description="Name of the warehouse", example="Main Warehouse"),
 *     @OA\Property(property="address", type="string", description="Address of the warehouse", example="123 Main St, Cityville"),
 *     @OA\Property(property="code", type="string", description="Unique code for the warehouse", example="MW001"),
 * )
 *
 * @OA\Schema(
 *     schema="WarehouseResource",
 *     type="object",
 *     title="Warehouse Resource Schema",
 *     description="Representation of a warehouse",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the warehouse", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the warehouse", example="Main Warehouse"),
 *     @OA\Property(property="address", type="string", description="Address of the warehouse", example="123 Main St, Cityville"),
 *     @OA\Property(property="code", type="string", description="Unique code for the warehouse", example="MW001"),
 * )
 */
class WarehouseController extends Controller
{
    /**
     * @var WarehouseService
     */
    protected WarehouseService $warehouseService;

    /**
     * Constructor.
     *
     * @param WarehouseService $warehouseService
     */
    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * Retrieve all warehouses.
     *
     * @OA\Get(
     *     path="/api/warehouses",
     *     summary="List all warehouses",
     *     tags={"Warehouses"},
     *     @OA\Response(
     *         response=200,
     *         description="List of warehouses retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/WarehouseResource")
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
        return WarehouseResource::collection($this->warehouseService->getAll());
    }

    /**
     * Create a new warehouse.
     *
     * @OA\Post(
     *     path="/api/warehouses",
     *     summary="Create a new warehouse",
     *     tags={"Warehouses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Warehouse created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseResource")
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
    public function store(WarehouseRequest $request)
    {
        try {
            return new WarehouseResource($this->warehouseService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific warehouse by ID.
     *
     * @OA\Get(
     *     path="/api/warehouses/{id}",
     *     summary="Get a warehouse by ID",
     *     tags={"Warehouses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Warehouse ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warehouse retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Warehouse not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return WarehouseResource::make($this->warehouseService->getById($id));
    }

    /**
     * Update an existing warehouse.
     *
     * @OA\Put(
     *     path="/api/warehouses/{id}",
     *     summary="Update a warehouse",
     *     tags={"Warehouses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Warehouse ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warehouse updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Warehouse not found"
     *     )
     * )
     */
    public function update(WarehouseRequest $request, int $id)
    {
        try {
            return new WarehouseResource($this->warehouseService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a warehouse.
     *
     * @OA\Delete(
     *     path="/api/warehouses/{id}",
     *     summary="Delete a warehouse",
     *     tags={"Warehouses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Warehouse ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warehouse deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Warehouse not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->warehouseService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
