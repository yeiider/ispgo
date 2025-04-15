<?php

namespace App\Http\Controllers\API\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Services\App\Models\Invoice\InvoiceService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Invoices",
 *     description="API Endpoints for managing invoices"
 * )
 *
 * @OA\Schema(
 *     schema="InvoiceRequest",
 *     type="object",
 *     title="Invoice Request Schema",
 *     description="Schema for creating or updating invoices",
 *     required={"increment_id", "service_id", "customer_id", "customer_name", "user_id", "subtotal", "tax", "total", "amount", "status"},
 *     @OA\Property(property="increment_id", type="string", description="Unique increment ID of the invoice", example="INV1001"),
 *     @OA\Property(property="service_id", type="integer", description="ID of the service associated with the invoice", example=2),
 *     @OA\Property(property="customer_id", type="integer", description="ID of the customer", example=1),
 *     @OA\Property(property="customer_name", type="string", description="Name of the customer", example="John Doe"),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user who created the invoice", example=3),
 *     @OA\Property(property="subtotal", type="number", format="float", description="Subtotal of the invoice", example=1000.50),
 *     @OA\Property(property="tax", type="number", format="float", description="Tax applied to the invoice", example=200.00),
 *     @OA\Property(property="total", type="number", format="float", description="Total amount of the invoice", example=1200.50),
 *     @OA\Property(property="discount", type="number", format="float", description="Discount applied (if any)", example=50.00),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount paid", example=1150.50),
 *     @OA\Property(property="outstanding_balance", type="number", format="float", description="Outstanding balance for the invoice", example=50.00),
 *     @OA\Property(property="issue_date", type="string", format="date", description="Date the invoice was issued", example="2023-10-20"),
 *     @OA\Property(property="due_date", type="string", format="date", description="Due payment date of the invoice", example="2023-11-20"),
 *     @OA\Property(property="status", type="string", enum={"paid", "unpaid", "overdue", "canceled"}, description="Status of the invoice", example="paid"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method used for the invoice", example="Credit Card"),
 *     @OA\Property(property="notes", type="string", description="Additional notes for the invoice", example="Paid in full"),
 *     @OA\Property(property="payment_support", type="string", description="Additional payment support metadata", example="Ref#_PAYMENT123"),
 *     @OA\Property(property="daily_box_id", type="integer", description="ID of the daily box entry for the invoice", example=4),
 *     @OA\Property(property="created_by", type="integer", description="User ID of the creator", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="User ID of the updater (if applicable)", example=2),
 *     @OA\Property(property="additional_information", type="object", description="Additional information JSON", example={"extra_field": "value"}),
 * )
 *
 * @OA\Schema(
 *     schema="InvoiceResource",
 *     type="object",
 *     title="Invoice Resource Schema",
 *     description="Representation of an invoice",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/InvoiceRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier for the invoice", example=10)
 *         )
 *     }
 * )
 */
class InvoiceController extends Controller
{
    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * Constructor.
     *
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Retrieve all invoices.
     *
     * @OA\Get(
     *     path="/api/invoices",
     *     summary="List all invoices",
     *     tags={"Invoices"},
     *     @OA\Response(
     *         response=200,
     *         description="List of invoices retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InvoiceResource")
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
        return InvoiceResource::collection($this->invoiceService->getAll());
    }

    /**
     * Create a new invoice.
     *
     * @OA\Post(
     *     path="/api/invoices",
     *     summary="Create a new invoice",
     *     tags={"Invoices"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Invoice created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceResource")
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
    public function store(InvoiceRequest $request)
    {
        try {
            return new InvoiceResource($this->invoiceService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific invoice by ID.
     *
     * @OA\Get(
     *     path="/api/invoices/{id}",
     *     summary="Get an invoice by ID",
     *     tags={"Invoices"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Invoice ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return InvoiceResource::make($this->invoiceService->getById($id));
    }

    /**
     * Update an existing invoice.
     *
     * @OA\Put(
     *     path="/api/invoices/{id}",
     *     summary="Update an invoice",
     *     tags={"Invoices"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Invoice ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found"
     *     )
     * )
     */
    public function update(InvoiceRequest $request, int $id)
    {
        try {
            return new InvoiceResource($this->invoiceService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an invoice.
     *
     * @OA\Delete(
     *     path="/api/invoices/{id}",
     *     summary="Delete an invoice",
     *     tags={"Invoices"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Invoice ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->invoiceService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
