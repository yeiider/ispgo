<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\TaxDetailRequest;
use App\Http\Resources\Customers\TaxDetailResource;
use App\Services\Customers\TaxDetailService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Tax Details",
 *     description="API Endpoints for managing tax details"
 * )
 *
 * @OA\Schema(
 *     schema="TaxDetailRequest",
 *     type="object",
 *     title="Tax Detail Request Schema",
 *     description="Schema for creating or updating tax details",
 *     required={"customer_id", "tax_identification_type", "tax_identification_number", "taxpayer_type", "fiscal_regime"},
 *     @OA\Property(property="customer_id", type="integer", description="ID of the customer", example=1),
 *     @OA\Property(property="tax_identification_type", type="string", description="Type of tax identification document", example="TIN"),
 *     @OA\Property(property="tax_identification_number", type="string", description="Number of the tax identification document", example="123456789"),
 *     @OA\Property(property="taxpayer_type", type="string", description="Type of taxpayer", example="Regular"),
 *     @OA\Property(property="fiscal_regime", type="string", description="Fiscal regime of the customer", example="General Regime"),
 *     @OA\Property(property="business_name", type="string", description="Business name of the customer (if applicable)", example="My Business LLC"),
 *     @OA\Property(property="enable_billing", type="integer", enum={1, 0}, description="Enable billing for this customer", example=1),
 *     @OA\Property(property="send_notifications", type="integer", enum={1, 0}, description="Enable notifications for this customer", example=1),
 *     @OA\Property(property="send_invoice", type="integer", enum={1, 0}, description="Enable invoice sending for this customer", example=1),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the tax detail", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who last updated the tax detail", example=2),
 * )
 *
 * @OA\Schema(
 *     schema="TaxDetailResource",
 *     type="object",
 *     title="Tax Detail Resource Schema",
 *     description="Representation of a tax detail",
 *     @OA\Property(property="id", type="integer", description="Tax detail ID", example=1),
 *     @OA\Property(property="customer_id", type="integer", description="Customer ID linked to the tax detail", example=1),
 *     @OA\Property(property="tax_identification_type", type="string", description="Type of tax identification document", example="TIN"),
 *     @OA\Property(property="tax_identification_number", type="string", description="Number of the tax identification document", example="123456789"),
 *     @OA\Property(property="taxpayer_type", type="string", description="Type of taxpayer", example="Regular"),
 *     @OA\Property(property="fiscal_regime", type="string", description="Fiscal regime", example="General Regime"),
 *     @OA\Property(property="business_name", type="string", description="Business name", example="My Business LLC"),
 *     @OA\Property(property="enable_billing", type="integer", description="Enable billing (1 for true, 0 for false)", example=1),
 *     @OA\Property(property="send_notifications", type="integer", description="Send notifications (1 for true, 0 for false)", example=1),
 *     @OA\Property(property="send_invoice", type="integer", description="Send invoices (1 for true, 0 for false)", example=1),
 *     @OA\Property(property="created_by", type="integer", description="ID of the creator", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the last updater", example=2),
 * )
 */
class TaxDetailController extends Controller
{
    /**
     * @var TaxDetailService
     */
    protected TaxDetailService $taxDetailService;

    /**
     * Constructor.
     *
     * @param TaxDetailService $taxDetailService
     */
    public function __construct(TaxDetailService $taxDetailService)
    {
        $this->taxDetailService = $taxDetailService;
    }

    /**
     * Retrieve all tax details.
     *
     * @OA\Get(
     *     path="/api/v1/tax-details",
     *     summary="List all tax details",
     *     tags={"Tax Details"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tax details retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaxDetailResource")
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
        return TaxDetailResource::collection($this->taxDetailService->getAll());
    }

    /**
     * Create a new tax detail.
     *
     * @OA\Post(
     *     path="/api/v1/tax-details",
     *     summary="Create a new tax detail",
     *     tags={"Tax Details"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaxDetailRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tax detail created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxDetailResource")
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
    public function store(TaxDetailRequest $request)
    {
        try {
            return new TaxDetailResource($this->taxDetailService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific tax detail by ID.
     *
     * @OA\Get(
     *     path="/api/v1/tax-details/{id}",
     *     summary="Get a tax detail by ID",
     *     tags={"Tax Details"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Tax detail ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax detail retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxDetailResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax detail not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TaxDetailResource::make($this->taxDetailService->getById($id));
    }

    /**
     * Update an existing tax detail.
     *
     * @OA\Put(
     *     path="/api/v1/tax-details/{id}",
     *     summary="Update a tax detail",
     *     tags={"Tax Details"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Tax detail ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaxDetailRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax detail updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaxDetailResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax detail not found"
     *     )
     * )
     */
    public function update(TaxDetailRequest $request, int $id)
    {
        try {
            return new TaxDetailResource($this->taxDetailService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a tax detail.
     *
     * @OA\Delete(
     *     path="/api/v1/tax-details/{id}",
     *     summary="Delete a tax detail",
     *     tags={"Tax Details"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Tax detail ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax detail deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax detail not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->taxDetailService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
