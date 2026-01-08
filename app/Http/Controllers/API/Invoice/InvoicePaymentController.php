<?php

namespace App\Http\Controllers\API\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoicePaymentRequest;
use App\Http\Resources\Invoice\InvoicePaymentResource;
use App\Services\Invoice\InvoicePaymentService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="InvoicePayments",
 *     description="API endpoints for managing invoice payments"
 * )
 */
class InvoicePaymentController extends Controller
{
    /**
     * @var InvoicePaymentService
     */
    protected InvoicePaymentService $invoicePaymentService;

    /**
     * InvoicePaymentController constructor.
     *
     * @param InvoicePaymentService $invoicePaymentService
     */
    public function __construct(InvoicePaymentService $invoicePaymentService)
    {
        $this->invoicePaymentService = $invoicePaymentService;
    }

    /**
     * Display a listing of invoice payments.
     *
     * @OA\Get(
     *     path="/api/v1/invoice-payments",
     *     tags={"InvoicePayments"},
     *     summary="Get all invoice payments",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InvoicePaymentResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return InvoicePaymentResource::collection($this->invoicePaymentService->getAll());
    }

    /**
     * Store a newly created invoice payment.
     *
     * @OA\Post(
     *     path="/api/v1/invoice-payments",
     *     tags={"InvoicePayments"},
     *     summary="Create a new invoice payment",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/InvoicePaymentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicePaymentResource")
     *     )
     * )
     */
    public function store(InvoicePaymentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            return new InvoicePaymentResource($this->invoicePaymentService->save($data));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified invoice payment.
     *
     * @OA\Get(
     *     path="/api/v1/invoice-payments/{id}",
     *     tags={"InvoicePayments"},
     *     summary="Get invoice payment by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicePaymentResource")
     *     )
     * )
     */
    public function show(int $id)
    {
        return InvoicePaymentResource::make($this->invoicePaymentService->getById($id));
    }

    /**
     * Update the specified invoice payment.
     *
     * @OA\Put(
     *     path="/api/v1/invoice-payments/{id}",
     *     tags={"InvoicePayments"},
     *     summary="Update invoice payment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/InvoicePaymentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicePaymentResource")
     *     )
     * )
     */
    public function update(InvoicePaymentRequest $request, int $id)
    {
        try {
            return new InvoicePaymentResource($this->invoicePaymentService->update($request->validated(), $id));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified invoice payment.
     *
     * @OA\Delete(
     *     path="/api/v1/invoice-payments/{id}",
     *     tags={"InvoicePayments"},
     *     summary="Delete invoice payment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Payment deleted successfully"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->invoicePaymentService->deleteById($id);
            return response()->json(['message' => 'Payment deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
