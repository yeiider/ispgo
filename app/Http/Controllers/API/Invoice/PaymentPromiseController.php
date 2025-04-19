<?php

namespace App\Http\Controllers\API\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\PaymentPromiseRequest;
use App\Http\Resources\Invoice\PaymentPromiseResource;
use App\Services\Invoice\PaymentPromiseService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="PaymentPromises",
 *     description="API Endpoints for managing payment promises"
 * )
 *
 * @OA\Schema(
 *     schema="PaymentPromiseRequest",
 *     type="object",
 *     title="Payment Promise Request Schema",
 *     description="Schema for creating or updating payment promises",
 *     required={"invoice_id", "customer_id", "user_id", "amount", "promise_date", "status"},
 *     @OA\Property(property="invoice_id", type="integer", description="ID of the associated invoice", example=101),
 *     @OA\Property(property="customer_id", type="integer", description="ID of the customer making the promise", example=5),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user responsible for recording the promise", example=2),
 *     @OA\Property(property="amount", type="number", format="float", description="Promised payment amount", example=500.00),
 *     @OA\Property(property="promise_date", type="string", format="date", description="Date by which payment is promised", example="2023-11-01"),
 *     @OA\Property(property="notes", type="string", description="Optional notes related to the promise", example="Partial payment for outstanding invoice"),
 *     @OA\Property(property="status", type="string", enum={"pending", "fulfilled", "cancelled"}, description="Status of the payment promise", example="pending")
 * )
 *
 * @OA\Schema(
 *     schema="PaymentPromiseResource",
 *     type="object",
 *     title="Payment Promise Resource Schema",
 *     description="Representation of a payment promise",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/PaymentPromiseRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the payment promise", example=5)
 *         )
 *     }
 * )
 */
class PaymentPromiseController extends Controller
{
    /**
     * @var PaymentPromiseService
     */
    protected PaymentPromiseService $paymentPromiseService;

    /**
     * Constructor.
     *
     * @param PaymentPromiseService $paymentPromiseService
     */
    public function __construct(PaymentPromiseService $paymentPromiseService)
    {
        $this->paymentPromiseService = $paymentPromiseService;
    }

    /**
     * Retrieve all payment promises.
     *
     * @OA\Get(
     *     path="/api/payment-promises",
     *     summary="List all payment promises",
     *     tags={"PaymentPromises"},
     *     @OA\Response(
     *         response=200,
     *         description="List of payment promises retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PaymentPromiseResource")
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
        return PaymentPromiseResource::collection($this->paymentPromiseService->getAll());
    }

    /**
     * Create a new payment promise.
     *
     * @OA\Post(
     *     path="/api/payment-promises",
     *     summary="Create a new payment promise",
     *     tags={"PaymentPromises"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PaymentPromiseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment promise created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PaymentPromiseResource")
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
    public function store(PaymentPromiseRequest $request)
    {
        try {
            return new PaymentPromiseResource($this->paymentPromiseService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific payment promise by ID.
     *
     * @OA\Get(
     *     path="/api/payment-promises/{id}",
     *     summary="Get a payment promise by ID",
     *     tags={"PaymentPromises"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Payment promise ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment promise retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PaymentPromiseResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment promise not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return PaymentPromiseResource::make($this->paymentPromiseService->getById($id));
    }

    /**
     * Update an existing payment promise.
     *
     * @OA\Put(
     *     path="/api/payment-promises/{id}",
     *     summary="Update a payment promise",
     *     tags={"PaymentPromises"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Payment promise ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PaymentPromiseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment promise updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PaymentPromiseResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment promise not found"
     *     )
     * )
     */
    public function update(PaymentPromiseRequest $request, int $id)
    {
        try {
            return new PaymentPromiseResource($this->paymentPromiseService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a payment promise.
     *
     * @OA\Delete(
     *     path="/api/payment-promises/{id}",
     *     summary="Delete a payment promise",
     *     tags={"PaymentPromises"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Payment promise ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment promise deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment promise not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->paymentPromiseService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
