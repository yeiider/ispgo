<?php

namespace App\Http\Controllers\API\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\CashRegisterRequest;
use App\Http\Resources\Finance\CashRegisterResource;
use App\Services\Finance\CashRegisterService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Cash Registers",
 *     description="API Endpoints for managing cash registers"
 * )
 *
 * @OA\Schema(
 *     schema="CashRegisterRequest",
 *     type="object",
 *     title="Cash Register Request Schema",
 *     description="Schema for creating or updating cash registers",
 *     required={"initial_balance", "current_balance"},
 *     @OA\Property(property="initial_balance", type="number", format="float", description="Initial balance of the cash register", example=1000.50),
 *     @OA\Property(property="current_balance", type="number", format="float", description="Current balance of the cash register", example=800.45),
 * )
 *
 * @OA\Schema(
 *     schema="CashRegisterResource",
 *     type="object",
 *     title="Cash Register Resource Schema",
 *     description="Representation of a cash register",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the cash register", example=1),
 *     @OA\Property(property="initial_balance", type="number", format="float", description="Initial balance of the cash register", example=1000.50),
 *     @OA\Property(property="current_balance", type="number", format="float", description="Current balance of the cash register", example=800.45),
 * )
 */
class CashRegisterController extends Controller
{
    /**
     * @var CashRegisterService
     */
    protected CashRegisterService $cashRegisterService;

    /**
     * Constructor.
     *
     * @param CashRegisterService $cashRegisterService
     */
    public function __construct(CashRegisterService $cashRegisterService)
    {
        $this->cashRegisterService = $cashRegisterService;
    }

    /**
     * Retrieve all cash registers.
     *
     * @OA\Get(
     *     path="/api/v1/cash-registers",
     *     summary="List all cash registers",
     *     tags={"Cash Registers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of cash registers retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CashRegisterResource")
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
        return CashRegisterResource::collection($this->cashRegisterService->getAll());
    }

    /**
     * Create a new cash register.
     *
     * @OA\Post(
     *     path="/api/v1/cash-registers",
     *     summary="Create a new cash register",
     *     tags={"Cash Registers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CashRegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cash register created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CashRegisterResource")
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
    public function store(CashRegisterRequest $request)
    {
        try {
            return new CashRegisterResource($this->cashRegisterService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific cash register by ID.
     *
     * @OA\Get(
     *     path="/api/v1/cash-registers/{id}",
     *     summary="Get a cash register by ID",
     *     tags={"Cash Registers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Cash register ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cash register retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CashRegisterResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cash register not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return CashRegisterResource::make($this->cashRegisterService->getById($id));
    }

    /**
     * Update an existing cash register.
     *
     * @OA\Put(
     *     path="/api/v1/cash-registers/{id}",
     *     summary="Update a cash register",
     *     tags={"Cash Registers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Cash register ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CashRegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cash register updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CashRegisterResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cash register not found"
     *     )
     * )
     */
    public function update(CashRegisterRequest $request, int $id)
    {
        try {
            return new CashRegisterResource($this->cashRegisterService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a cash register.
     *
     * @OA\Delete(
     *     path="/api/v1/cash-registers/{id}",
     *     summary="Delete a cash register",
     *     tags={"Cash Registers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Cash register ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cash register deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cash register not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->cashRegisterService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
