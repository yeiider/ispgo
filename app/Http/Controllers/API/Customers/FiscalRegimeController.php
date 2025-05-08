<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\FiscalRegimeRequest;
use App\Http\Resources\Customers\FiscalRegimeResource;
use App\Services\Customers\FiscalRegimeService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Fiscal Regimes",
 *     description="API Endpoints for managing fiscal regimes"
 * )
 *
 * @OA\Schema(
 *     schema="FiscalRegimeRequest",
 *     type="object",
 *     title="Fiscal Regime Request Schema",
 *     description="Schema for creating or updating a fiscal regime",
 *     required={"code", "name"},
 *     @OA\Property(property="code", type="string", description="Unique code of the fiscal regime", example="FR001"),
 *     @OA\Property(property="name", type="string", description="Name of the fiscal regime", example="General Regime"),
 * )
 *
 * @OA\Schema(
 *     schema="FiscalRegimeResource",
 *     type="object",
 *     title="Fiscal Regime Resource Schema",
 *     description="Representation of a fiscal regime",
 *     @OA\Property(property="id", type="integer", description="Fiscal regime ID", example=1),
 *     @OA\Property(property="code", type="string", description="Unique code of the fiscal regime", example="FR001"),
 *     @OA\Property(property="name", type="string", description="Name of the fiscal regime", example="General Regime"),
 * )
 */
class FiscalRegimeController extends Controller
{
    /**
     * @var FiscalRegimeService
     */
    protected FiscalRegimeService $fiscalRegimeService;

    /**
     * Constructor.
     *
     * @param FiscalRegimeService $fiscalRegimeService
     */
    public function __construct(FiscalRegimeService $fiscalRegimeService)
    {
        $this->fiscalRegimeService = $fiscalRegimeService;
    }

    /**
     * Retrieve all fiscal regimes.
     *
     * @OA\Get(
     *     path="/api/v1/fiscal-regimes",
     *     summary="List all fiscal regimes",
     *     tags={"Fiscal Regimes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of fiscal regimes retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/FiscalRegimeResource")
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
        return FiscalRegimeResource::collection($this->fiscalRegimeService->getAll());
    }

    /**
     * Create a new fiscal regime.
     *
     * @OA\Post(
     *     path="/api/fiscal-regimes",
     *     summary="Create a new fiscal regime",
     *     tags={"Fiscal Regimes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FiscalRegimeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Fiscal regime created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/FiscalRegimeResource")
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
    public function store(FiscalRegimeRequest $request)
    {
        try {
            return new FiscalRegimeResource($this->fiscalRegimeService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a fiscal regime by ID.
     *
     * @OA\Get(
     *     path="/api/fiscal-regimes/{id}",
     *     summary="Get a fiscal regime by ID",
     *     tags={"Fiscal Regimes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Fiscal regime ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fiscal regime retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/FiscalRegimeResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fiscal regime not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return FiscalRegimeResource::make($this->fiscalRegimeService->getById($id));
    }

    /**
     * Update an existing fiscal regime.
     *
     * @OA\Put(
     *     path="/api/fiscal-regimes/{id}",
     *     summary="Update a fiscal regime",
     *     tags={"Fiscal Regimes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Fiscal regime ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FiscalRegimeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fiscal regime updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/FiscalRegimeResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fiscal regime not found"
     *     )
     * )
     */
    public function update(FiscalRegimeRequest $request, int $id)
    {
        try {
            return new FiscalRegimeResource($this->fiscalRegimeService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a fiscal regime.
     *
     * @OA\Delete(
     *     path="/api/fiscal-regimes/{id}",
     *     summary="Delete a fiscal regime",
     *     tags={"Fiscal Regimes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Fiscal regime ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fiscal regime deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fiscal regime not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->fiscalRegimeService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
