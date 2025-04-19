<?php

namespace App\Http\Controllers\API\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\TransactionRequest;
use App\Http\Resources\Finance\TransactionResource;
use App\Services\Finance\TransactionService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="API Endpoints for managing financial transactions"
 * )
 *
 * @OA\Schema(
 *     schema="TransactionRequest",
 *     type="object",
 *     title="Transaction Request Schema",
 *     description="Schema for creating or updating financial transactions",
 *     required={"description", "amount", "date", "type", "payment_method"},
 *     @OA\Property(property="description", type="string", description="Description of the transaction", example="Purchase Payment"),
 *     @OA\Property(property="amount", type="number", format="float", description="Transaction amount", example=500.00),
 *     @OA\Property(property="date", type="string", format="date", description="Transaction date", example="2023-11-19"),
 *     @OA\Property(property="type", type="string", description="Type of transaction (e.g., income, expense)", example="income"),
 *     @OA\Property(property="payment_method", type="string", description="Method of payment for the transaction", example="Credit Card"),
 *     @OA\Property(property="category", type="string", description="Category for the transaction", example="Office Supplies"),
 *     @OA\Property(property="cash_register_id", type="integer", description="ID of the associated cash register", example=1),
 * )
 *
 * @OA\Schema(
 *     schema="TransactionResource",
 *     type="object",
 *     title="Transaction Resource Schema",
 *     description="Representation of a financial transaction",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the transaction", example=1),
 *     @OA\Property(property="description", type="string", description="Description of the transaction", example="Purchase Payment"),
 *     @OA\Property(property="amount", type="number", format="float", description="Transaction amount", example=500.00),
 *     @OA\Property(property="date", type="string", format="date", description="Transaction date", example="2023-11-19"),
 *     @OA\Property(property="type", type="string", description="Type of transaction (e.g., income, expense)", example="income"),
 *     @OA\Property(property="payment_method", type="string", description="Method of payment for the transaction", example="Credit Card"),
 *     @OA\Property(property="category", type="string", description="Category for the transaction", example="Office Supplies"),
 *     @OA\Property(property="cash_register_id", type="integer", description="ID of the associated cash register", example=1),
 * )
 */
class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    protected TransactionService $transactionService;

    /**
     * Constructor.
     *
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Retrieve all transactions.
     *
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="List all transactions",
     *     tags={"Transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="List of transactions retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TransactionResource")
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
        return TransactionResource::collection($this->transactionService->getAll());
    }

    /**
     * Create a new transaction.
     *
     * @OA\Post(
     *     path="/api/transactions",
     *     summary="Create a new transaction",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
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
    public function store(TransactionRequest $request)
    {
        try {
            return new TransactionResource($this->transactionService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific transaction by ID.
     *
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     summary="Get a transaction by ID",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Transaction ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TransactionResource::make($this->transactionService->getById($id));
    }

    /**
     * Update an existing transaction.
     *
     * @OA\Put(
     *     path="/api/transactions/{id}",
     *     summary="Update a transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Transaction ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */
    public function update(TransactionRequest $request, int $id)
    {
        try {
            return new TransactionResource($this->transactionService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a transaction.
     *
     * @OA\Delete(
     *     path="/api/transactions/{id}",
     *     summary="Delete a transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Transaction ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->transactionService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
