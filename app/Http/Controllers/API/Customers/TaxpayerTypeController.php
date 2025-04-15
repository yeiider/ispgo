<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\TaxpayerTypeRequest;
use App\Http\Resources\Customers\TaxpayerTypeResource;
use App\Services\App\Models\Customers\TaxpayerTypeService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Taxpayer Types",
 *     description="API Endpoints for managing taxpayer types"
 * )
 *
 * @OA\Schema(
 *     schema="TaxpayerTypeRequest",
 *     type="object",
 *     title="Taxpayer Type Request Schema",
 *     description="Schema for creating or updating a taxpayer type",
 *     required={"code", "name"},
 *     @OA\Property(property="code", type="string", description="Unique code of the taxpayer type", example="REG001"),
 *     @OA\Property(property="name", type="string", description="Name of the taxpayer type", example="Individual"),
 * )
 *
 * @OA\Schema(
 *     schema="TaxpayerTypeResource",
 *     type="object",
 *     title="Taxpayer Type Resource Schema",
 *     description="Representation of a taxpayer type",
 *     @OA\Property(property="id", type="integer", description="Identifier of the taxpayer type", example=1),
 *     @OA\Property(property="code", type="string", description="Unique code of the taxpayer type", example="REG001"),
 *     @OA\Property(property="name", type="string", description="Name of the taxpayer type", example="Individual"),
 * )
 */
class TaxpayerTypeController extends Controller
{
    /**
     * @var TaxpayerTypeService
     */
    protected TaxpayerTypeService $taxpayerTypeService;

    /**
     * Constructor.
     *
     * @param TaxpayerTypeService $taxpayerTypeService
     */
    public function __construct(TaxpayerTypeService $taxpayerTypeService)
    {
        $this->taxpayerTypeService = $taxpayerTypeService;
    }

    /**
     * Retrieve all taxpayer types.
     *
     * @OA\Get(
     *     path="/api/taxpayer-types",
     *     summary="List all taxpayer types",
     *     tags={"Taxpayer Types"},
     *     @OA\Response(
     *         response=200,
     *         description="List of taxpayer types retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaxpayerTypeResource")
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
        return TaxpayerTypeResource::collection($this->taxpayerTypeService->getAll());
    }

    /**
     * Create a new taxpayer type.
     *
     * @OA\Post(
     *     path="/api/taxpayer-types",
     *     summary="Create a new taxpayer type",
     *     tags={"Taxpayer Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaxpayerTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Taxpayer type created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxpayerTypeResource")
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
    public function store(TaxpayerTypeRequest $request)
    {
        try {
            return new TaxpayerTypeResource($this->taxpayerTypeService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific taxpayer type by ID.
     *
     * @OA\Get(
     *     path="/api/taxpayer-types/{id}",
     *     summary="Get a taxpayer type by ID",
     *     tags={"Taxpayer Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Taxpayer type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Taxpayer type retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxpayerTypeResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Taxpayer type not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TaxpayerTypeResource::make($this->taxpayerTypeService->getById($id));
    }

    /**
     * Update an existing taxpayer type.
     *
     * @OA\Put(
     *     path="/api/taxpayer-types/{id}",
     *     summary="Update a taxpayer type",
     *     tags={"Taxpayer Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Taxpayer type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaxpayerTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Taxpayer type updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxpayerTypeResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Taxpayer type not found"
     *     )
     * )
     */
    public function update(TaxpayerTypeRequest $request, int $id)
    {
        try {
            return new TaxpayerTypeResource($this->taxpayerTypeService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a taxpayer type.
     *
     * @OA\Delete(
     *     path="/api/taxpayer-types/{id}",
     *     summary="Delete a taxpayer type",
     *     tags={"Taxpayer Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Taxpayer type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Taxpayer type deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Taxpayer type not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->taxpayerTypeService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
