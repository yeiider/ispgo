<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\SupplierRequest;
use App\Http\Resources\Inventory\SupplierResource;
use App\Services\App\Models\Inventory\SupplierService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Suppliers",
 *     description="API Endpoints for managing suppliers"
 * )
 *
 * @OA\Schema(
 *     schema="SupplierRequest",
 *     type="object",
 *     title="Supplier Request Schema",
 *     description="Schema for creating or updating suppliers",
 *     required={"name", "contact", "document", "email"},
 *     @OA\Property(property="name", type="string", description="Name of the supplier", example="Tech Supplies Inc."),
 *     @OA\Property(property="contact", type="string", description="Contact person or department", example="John Doe"),
 *     @OA\Property(property="document", type="string", description="Unique identification document of the supplier", example="12345XYZ"),
 *     @OA\Property(property="email", type="string", format="email", description="Email address of the supplier", example="contact@techsupplies.com"),
 *     @OA\Property(property="description", type="string", description="Short description of the supplier", example="Supplier of electronic parts"),
 *     @OA\Property(property="country", type="string", description="Country of the supplier", example="USA"),
 *     @OA\Property(property="city", type="string", description="City of the supplier", example="New York"),
 *     @OA\Property(property="postal_code", type="string", description="Postal/ZIP code of the supplier", example="10001"),
 *     @OA\Property(property="phone", type="string", description="Phone number of the supplier", example="+1 555-1234"),
 * )
 *
 * @OA\Schema(
 *     schema="SupplierResource",
 *     type="object",
 *     title="Supplier Resource Schema",
 *     description="Representation of a supplier",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the supplier", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the supplier", example="Tech Supplies Inc."),
 *     @OA\Property(property="contact", type="string", description="Contact person or department", example="John Doe"),
 *     @OA\Property(property="document", type="string", description="Unique identification document of the supplier", example="12345XYZ"),
 *     @OA\Property(property="email", type="string", format="email", description="Email address of the supplier", example="contact@techsupplies.com"),
 *     @OA\Property(property="description", type="string", description="Short description of the supplier", example="Supplier of electronic parts"),
 *     @OA\Property(property="country", type="string", description="Country of the supplier", example="USA"),
 *     @OA\Property(property="city", type="string", description="City of the supplier", example="New York"),
 *     @OA\Property(property="postal_code", type="string", description="Postal/ZIP code of the supplier", example="10001"),
 *     @OA\Property(property="phone", type="string", description="Phone number of the supplier", example="+1 555-1234"),
 * )
 */
class SupplierController extends Controller
{
    /**
     * @var SupplierService
     */
    protected SupplierService $supplierService;

    /**
     * Constructor.
     *
     * @param SupplierService $supplierService
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Retrieve all suppliers.
     *
     * @OA\Get(
     *     path="/api/suppliers",
     *     summary="List all suppliers",
     *     tags={"Suppliers"},
     *     @OA\Response(
     *         response=200,
     *         description="List of suppliers retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SupplierResource")
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
        return SupplierResource::collection($this->supplierService->getAll());
    }

    /**
     * Create a new supplier.
     *
     * @OA\Post(
     *     path="/api/suppliers",
     *     summary="Create a new supplier",
     *     tags={"Suppliers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SupplierRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Supplier created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierResource")
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
    public function store(SupplierRequest $request)
    {
        try {
            return new SupplierResource($this->supplierService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific supplier by ID.
     *
     * @OA\Get(
     *     path="/api/suppliers/{id}",
     *     summary="Get a supplier by ID",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Supplier ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Supplier not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return SupplierResource::make($this->supplierService->getById($id));
    }

    /**
     * Update an existing supplier.
     *
     * @OA\Put(
     *     path="/api/suppliers/{id}",
     *     summary="Update a supplier",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Supplier ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SupplierRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Supplier not found"
     *     )
     * )
     */
    public function update(SupplierRequest $request, int $id)
    {
        try {
            return new SupplierResource($this->supplierService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a supplier.
     *
     * @OA\Delete(
     *     path="/api/suppliers/{id}",
     *     summary="Delete a supplier",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Supplier ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Supplier not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->supplierService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
