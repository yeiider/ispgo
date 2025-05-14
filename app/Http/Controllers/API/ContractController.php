<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractRequest;
use App\Http\Resources\ContractResource;
use App\Services\ContractService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Contracts",
 *     description="API Endpoints for Contracts"
 * )
 *
 * @OA\Schema(
 *     schema="ContractRequest",
 *     type="object",
 *     title="Contract Request Schema",
 *     description="Schema used for creating or updating a Contract",
 *     required={"title", "start_date", "end_date"},
 *     @OA\Property(property="title", type="string", description="The title of the contract", example="Service Agreement"),
 *     @OA\Property(property="start_date", type="string", format="date", description="Start date of the contract", example="2023-01-01"),
 *     @OA\Property(property="end_date", type="string", format="date", description="End date of the contract", example="2023-12-31"),
 *     @OA\Property(property="details", type="string", description="Additional details about the contract", example="This contract covers IT services.")
 * )
 *
 * @OA\Schema(
 *     schema="ContractResource",
 *     type="object",
 *     title="Contract Resource Schema",
 *     description="Response structure for a contract",
 *     @OA\Property(property="id", type="integer", description="The ID of the contract", example=1),
 *     @OA\Property(property="title", type="string", description="The title of the contract", example="Service Agreement"),
 *     @OA\Property(property="start_date", type="string", format="date", description="Start date of the contract", example="2023-01-01"),
 *     @OA\Property(property="end_date", type="string", format="date", description="End date of the contract", example="2023-12-31"),
 *     @OA\Property(property="details", type="string", description="Additional details about the contract", example="This contract covers IT services."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="When the contract was created", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="When the contract was last updated", example="2023-10-01T12:45:00Z")
 * )
 */

class ContractController extends Controller
{
    /**
     * @var ContractService
     */
    protected ContractService $contractService;

    /**
     * DummyModel Constructor
     *
     * @param ContractService $contractService
     *
     */
    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * List all contracts.
     *
     * @OA\Get(
     *     path="/api/v1/contracts",
     *     summary="Get all Contracts",
     *     tags={"Contracts"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of Contracts retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ContractResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ContractResource::collection($this->contractService->getAll());
    }

    /**
     * Create a new contract.
     *
     * @OA\Post(
     *     path="/api/v1/contracts",
     *     summary="Create a new Contract",
     *     tags={"Contracts"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ContractRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contract created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ContractResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(ContractRequest $request): ContractResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new ContractResource($this->contractService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a contract by its ID.
     *
     * @OA\Get(
     *     path="/api/v1/contracts/{id}",
     *     summary="Retrieve a Contract by ID",
     *     tags={"Contracts"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Contract ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ContractResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Contract not found"
     *     )
     * )
     */
    public function show(int $id): ContractResource
    {
        return ContractResource::make($this->contractService->getById($id));
    }

    /**
     * Update an existing contract.
     *
     * @OA\Put(
     *     path="/api/v1/contracts/{id}",
     *     summary="Update an existing Contract",
     *     tags={"Contracts"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Contract ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ContractRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ContractResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */

    public function update(ContractRequest $request, int $id): ContractResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new ContractResource($this->contractService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a contract.
     *
     * @OA\Delete(
     *     path="/api/v1/contracts/{id}",
     *     summary="Delete a Contract",
     *     tags={"Contracts"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Contract ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->contractService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
