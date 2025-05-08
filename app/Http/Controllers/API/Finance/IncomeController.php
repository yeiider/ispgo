<?php

namespace App\Http\Controllers\API\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\IncomeRequest;
use App\Http\Resources\Finance\IncomeResource;
use App\Services\Finance\IncomeService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Incomes",
 *     description="API Endpoints for managing incomes"
 * )
 *
 * @OA\Schema(
 *     schema="IncomeRequest",
 *     type="object",
 *     title="Income Request Schema",
 *     description="Schema for creating or updating incomes",
 *     required={"description", "amount", "date", "payment_method"},
 *     @OA\Property(property="description", type="string", description="Income description", example="Monthly Subscription"),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the income", example=2000.50),
 *     @OA\Property(property="date", type="string", format="date", description="Date of the income", example="2023-11-01"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method for the income", example="Bank Transfer"),
 *     @OA\Property(property="category", type="string", description="Category of the income", example="Services"),
 *     @OA\Property(property="customer_id", type="integer", description="ID of the customer associated with the income", example=1),
 *     @OA\Property(property="invoice_id", type="integer", description="ID of the invoice related to the income", example=101),
 * )
 *
 * @OA\Schema(
 *     schema="IncomeResource",
 *     type="object",
 *     title="Income Resource Schema",
 *     description="Representation of an income",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the income", example=1),
 *     @OA\Property(property="description", type="string", description="Income description", example="Monthly Subscription"),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the income", example=2000.50),
 *     @OA\Property(property="date", type="string", format="date", description="Date of the income", example="2023-11-01"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method", example="Bank Transfer"),
 *     @OA\Property(property="category", type="string", description="Category of the income", example="Services"),
 *     @OA\Property(property="customer_id", type="integer", description="Customer associated with the income", example=1),
 *     @OA\Property(property="invoice_id", type="integer", description="Invoice associated with the income", example=101),
 * )
 */
class IncomeController extends Controller
{
    /**
     * @var IncomeService
     */
    protected IncomeService $incomeService;

    /**
     * Constructor.
     *
     * @param IncomeService $incomeService
     */
    public function __construct(IncomeService $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    /**
     * Retrieve all incomes.
     *
     * @OA\Get(
     *     path="/api/v1/incomes",
     *     summary="List all incomes",
     *     tags={"Incomes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of incomes retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/IncomeResource")
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
        return IncomeResource::collection($this->incomeService->getAll());
    }

    /**
     * Create a new income.
     *
     * @OA\Post(
     *     path="/api/v1/incomes",
     *     summary="Create a new income",
     *     tags={"Incomes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IncomeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Income created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IncomeResource")
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
    public function store(IncomeRequest $request)
    {
        try {
            return new IncomeResource($this->incomeService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific income by ID.
     *
     * @OA\Get(
     *     path="/api/v1/incomes/{id}",
     *     summary="Get an income by ID",
     *     tags={"Incomes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Income ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Income retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IncomeResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Income not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return IncomeResource::make($this->incomeService->getById($id));
    }

    /**
     * Update an existing income.
     *
     * @OA\Put(
     *     path="/api/v1/incomes/{id}",
     *     summary="Update an income",
     *     tags={"Incomes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Income ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IncomeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Income updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IncomeResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Income not found"
     *     )
     * )
     */
    public function update(IncomeRequest $request, int $id)
    {
        try {
            return new IncomeResource($this->incomeService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an income.
     *
     * @OA\Delete(
     *     path="/api/v1/incomes/{id}",
     *     summary="Delete an income",
     *     tags={"Incomes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Income ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Income deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Income not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->incomeService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
