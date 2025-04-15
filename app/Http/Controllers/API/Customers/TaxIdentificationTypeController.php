<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\TaxIdentificationTypeRequest;
use App\Http\Resources\Customers\TaxIdentificationTypeResource;
use App\Services\App\Models\Customers\TaxIdentificationTypeService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Tax Identification Types",
 *     description="API Endpoints for managing tax identification types"
 * )
 *
 * @OA\Schema(
 *     schema="TaxIdentificationTypeRequest",
 *     type="object",
 *     title="Tax Identification Type Request Schema",
 *     description="Schema for creating or updating a tax identification type",
 *     required={"code", "name"},
 *     @OA\Property(property="code", type="string", description="Unique code of the tax identification type", example="TIN"),
 *     @OA\Property(property="name", type="string", description="Name of the tax identification type", example="Tax Identification Number"),
 * )
 *
 * @OA\Schema(
 *     schema="TaxIdentificationTypeResource",
 *     type="object",
 *     title="Tax Identification Type Resource Schema",
 *     description="Representation of a tax identification type",
 *     @OA\Property(property="code", type="string", description="Unique code for the tax identification type", example="TIN"),
 *     @OA\Property(property="name", type="string", description="Name of the tax identification type", example="Tax Identification Number"),
 * )
 */
class TaxIdentificationTypeController extends Controller
{
    /**
     * @var TaxIdentificationTypeService
     */
    protected TaxIdentificationTypeService $taxIdentificationTypeService;

    /**
     * Constructor.
     *
     * @param TaxIdentificationTypeService $taxIdentificationTypeService
     */
    public function __construct(TaxIdentificationTypeService $taxIdentificationTypeService)
    {
        $this->taxIdentificationTypeService = $taxIdentificationTypeService;
    }

    /**
     * Retrieve all tax identification types.
     *
     * @OA\Get(
     *     path="/api/tax-identification-types",
     *     summary="List all tax identification types",
     *     tags={"Tax Identification Types"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tax identification types retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaxIdentificationTypeResource")
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
        return TaxIdentificationTypeResource::collection($this->taxIdentificationTypeService->getAll());
    }

    /**
     * Create a new tax identification type.
     *
     * @OA\Post(
     *     path="/api/tax-identification-types",
     *     summary="Create a new tax identification type",
     *     tags={"Tax Identification Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaxIdentificationTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tax identification type created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxIdentificationTypeResource")
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
    public function store(TaxIdentificationTypeRequest $request)
    {
        try {
            return new TaxIdentificationTypeResource($this->taxIdentificationTypeService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific tax identification type by ID.
     *
     * @OA\Get(
     *     path="/api/tax-identification-types/{id}",
     *     summary="Get a tax identification type by ID",
     *     tags={"Tax Identification Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Tax identification type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax identification type retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxIdentificationTypeResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax identification type not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TaxIdentificationTypeResource::make($this->taxIdentificationTypeService->getById($id));
    }

    /**
     * Update an existing tax identification type.
     *
     * @OA\Put(
     *     path="/api/tax-identification-types/{id}",
     *     summary="Update a tax identification type",
     *     tags={"Tax Identification Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Tax identification type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaxIdentificationTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax identification type updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxIdentificationTypeResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax identification type not found"
     *     )
     * )
     */
    public function update(TaxIdentificationTypeRequest $request, int $id)
    {
        try {
            return new TaxIdentificationTypeResource($this->taxIdentificationTypeService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a tax identification type.
     *
     * @OA\Delete(
     *     path="/api/tax-identification-types/{id}",
     *     summary="Delete a tax identification type",
     *     tags={"Tax Identification Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Tax identification type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax identification type deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax identification type not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->taxIdentificationTypeService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
