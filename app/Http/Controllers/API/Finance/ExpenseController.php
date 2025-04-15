<?php

namespace App\Http\Controllers\API\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\ExpenseRequest;
use App\Http\Resources\Finance\ExpenseResource;
use App\Services\App\Models\Finance\ExpenseService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Expenses",
 *     description="API Endpoints for managing expenses"
 * )
 *
 * @OA\Schema(
 *     schema="ExpenseRequest",
 *     type="object",
 *     title="Expense Request Schema",
 *     description="Schema for creating or updating expenses",
 *     required={"description", "amount", "date", "payment_method"},
 *     @OA\Property(property="description", type="string", description="Expense description", example="Office Supplies"),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the expense", example=150.75),
 *     @OA\Property(property="date", type="string", format="date", description="Date of the expense", example="2023-11-01"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method for the expense", example="Credit Card"),
 *     @OA\Property(property="category", type="string", description="Category of the expense", example="Office"),
 *     @OA\Property(property="supplier_id", type="integer", description="ID of the supplier associated with the expense", example=2),
 * )
 *
 * @OA\Schema(
 *     schema="ExpenseResource",
 *     type="object",
 *     title="Expense Resource Schema",
 *     description="Representation of an expense",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the expense", example=1),
 *     @OA\Property(property="description", type="string", description="Expense description", example="Office Supplies"),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the expense", example=150.75),
 *     @OA\Property(property="date", type="string", format="date", description="Date of the expense", example="2023-11-01"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method", example="Credit Card"),
 *     @OA\Property(property="category", type="string", description="Category of the expense", example="Office"),
 *     @OA\Property(property="supplier_id", type="integer", description="ID of the supplier associated with the expense", example=2),
 * )
 */
class ExpenseController extends Controller
{
    /**
     * @var ExpenseService
     */
    protected ExpenseService $expenseService;

    /**
     * Constructor.
     *
     * @param ExpenseService $expenseService
     */
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Retrieve all expenses.
     *
     * @OA\Get(
     *     path="/api/expenses",
     *     summary="List all expenses",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="List of expenses retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ExpenseResource")
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
        return ExpenseResource::collection($this->expenseService->getAll());
    }

    /**
     * Create a new expense.
     *
     * @OA\Post(
     *     path="/api/expenses",
     *     summary="Create a new expense",
     *     tags={"Expenses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Expense created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
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
    public function store(ExpenseRequest $request)
    {
        try {
            return new ExpenseResource($this->expenseService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific expense by ID.
     *
     * @OA\Get(
     *     path="/api/expenses/{id}",
     *     summary="Get an expense by ID",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Expense ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return ExpenseResource::make($this->expenseService->getById($id));
    }

    /**
     * Update an existing expense.
     *
     * @OA\Put(
     *     path="/api/expenses/{id}",
     *     summary="Update an expense",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Expense ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */
    public function update(ExpenseRequest $request, int $id)
    {
        try {
            return new ExpenseResource($this->expenseService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an expense.
     *
     * @OA\Delete(
     *     path="/api/expenses/{id}",
     *     summary="Delete an expense",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Expense ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->expenseService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
