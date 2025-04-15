<?php

namespace App\Http\Controllers\API\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\DailyInvoiceBalanceRequest;
use App\Http\Resources\Invoice\DailyInvoiceBalanceResource;
use App\Services\App\Models\Invoice\DailyInvoiceBalanceService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="DailyInvoiceBalances",
 *     description="API Endpoints for managing daily invoice balances"
 * )
 *
 * @OA\Schema(
 *     schema="DailyInvoiceBalanceRequest",
 *     type="object",
 *     title="Daily Invoice Balance Request Schema",
 *     description="Schema for creating or updating daily invoice balances",
 *     required={"date", "total_invoices", "paid_invoices", "total_subtotal", "total_tax", "total_amount", "total_discount", "total_outstanding_balance", "total_revenue"},
 *     @OA\Property(property="date", type="string", format="date", description="Date for the invoice balance", example="2023-10-20"),
 *     @OA\Property(property="total_invoices", type="integer", description="Total number of invoices for the day", example=100),
 *     @OA\Property(property="paid_invoices", type="integer", description="Total number of paid invoices", example=80),
 *     @OA\Property(property="total_subtotal", type="number", format="float", description="Total subtotal amount for the day", example=5000.00),
 *     @OA\Property(property="total_tax", type="number", format="float", description="Total tax amount for the day", example=750.00),
 *     @OA\Property(property="total_amount", type="number", format="float", description="Total amount for the day including tax", example=5750.00),
 *     @OA\Property(property="total_discount", type="number", format="float", description="Total discount applied on all invoices", example=500.00),
 *     @OA\Property(property="total_outstanding_balance", type="number", format="float", description="Total outstanding balance for the day", example=250.00),
 *     @OA\Property(property="total_revenue", type="number", format="float", description="Total revenue for the day", example=5500.00),
 * )
 *
 * @OA\Schema(
 *     schema="DailyInvoiceBalanceResource",
 *     type="object",
 *     title="Daily Invoice Balance Resource Schema",
 *     description="Representation of a daily invoice balance",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the daily invoice balance", example=1),
 *     @OA\Property(property="date", type="string", format="date", description="The date for the balance", example="2023-10-20"),
 *     @OA\Property(property="total_invoices", type="integer", description="Total invoices for the day", example=100),
 *     @OA\Property(property="paid_invoices", type="integer", description="Invoices paid during the day", example=80),
 *     @OA\Property(property="total_subtotal", type="number", format="float", description="Total subtotal amount", example=5000.00),
 *     @OA\Property(property="total_tax", type="number", format="float", description="Total tax collected", example=750.00),
 *     @OA\Property(property="total_amount", type="number", format="float", description="Total amount including tax", example=5750.00),
 *     @OA\Property(property="total_discount", type="number", format="float", description="Discount applied to invoices", example=500.00),
 *     @OA\Property(property="total_outstanding_balance", type="number", format="float", description="Outstanding balances for the invoices", example=250.00),
 *     @OA\Property(property="total_revenue", type="number", format="float", description="Total revenue collected during the day", example=5500.00),
 * )
 */
class DailyInvoiceBalanceController extends Controller
{
    /**
     * @var DailyInvoiceBalanceService
     */
    protected DailyInvoiceBalanceService $dailyInvoiceBalanceService;

    /**
     * Constructor.
     *
     * @param DailyInvoiceBalanceService $dailyInvoiceBalanceService
     */
    public function __construct(DailyInvoiceBalanceService $dailyInvoiceBalanceService)
    {
        $this->dailyInvoiceBalanceService = $dailyInvoiceBalanceService;
    }

    /**
     * Retrieve all daily invoice balances.
     *
     * @OA\Get(
     *     path="/api/daily-invoice-balances",
     *     summary="List all daily invoice balances",
     *     tags={"DailyInvoiceBalances"},
     *     @OA\Response(
     *         response=200,
     *         description="List of daily invoice balances retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DailyInvoiceBalanceResource")
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
        return DailyInvoiceBalanceResource::collection($this->dailyInvoiceBalanceService->getAll());
    }

    /**
     * Create a new daily invoice balance entry.
     *
     * @OA\Post(
     *     path="/api/daily-invoice-balances",
     *     summary="Create a new daily invoice balance",
     *     tags={"DailyInvoiceBalances"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DailyInvoiceBalanceRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Daily invoice balance created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DailyInvoiceBalanceResource")
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
    public function store(DailyInvoiceBalanceRequest $request)
    {
        try {
            return new DailyInvoiceBalanceResource($this->dailyInvoiceBalanceService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific daily invoice balance entry by ID.
     *
     * @OA\Get(
     *     path="/api/daily-invoice-balances/{id}",
     *     summary="Get a daily invoice balance entry by ID",
     *     tags={"DailyInvoiceBalances"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Daily invoice balance entry ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daily invoice balance retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DailyInvoiceBalanceResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Daily invoice balance not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return DailyInvoiceBalanceResource::make($this->dailyInvoiceBalanceService->getById($id));
    }

    /**
     * Update an existing daily invoice balance.
     *
     * @OA\Put(
     *     path="/api/daily-invoice-balances/{id}",
     *     summary="Update a daily invoice balance",
     *     tags={"DailyInvoiceBalances"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Daily invoice balance ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DailyInvoiceBalanceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daily invoice balance updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DailyInvoiceBalanceResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Daily invoice balance not found"
     *     )
     * )
     */
    public function update(DailyInvoiceBalanceRequest $request, int $id)
    {
        try {
            return new DailyInvoiceBalanceResource($this->dailyInvoiceBalanceService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a daily invoice balance entry.
     *
     * @OA\Delete(
     *     path="/api/daily-invoice-balances/{id}",
     *     summary="Delete a daily invoice balance entry",
     *     tags={"DailyInvoiceBalances"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Daily invoice balance entry ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daily invoice balance entry deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Daily invoice balance not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->dailyInvoiceBalanceService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
