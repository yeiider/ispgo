<?php

namespace App\Http\Controllers\API\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\PlanRequest;
use App\Http\Resources\Services\PlanResource;
use App\Services\Services\PlanService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Plans",
 *     description="API Endpoints for managing service plans"
 * )
 *
 * @OA\Schema(
 *     schema="PlanRequest",
 *     type="object",
 *     title="Plan Request Schema",
 *     description="Schema for creating or updating service plans",
 *     required={"name", "download_speed", "upload_speed", "monthly_price", "plan_type", "modality_type", "status"},
 *     @OA\Property(property="name", type="string", maxLength=255, description="Name of the plan", example="High-Speed Internet Plan"),
 *     @OA\Property(property="description", type="string", description="Description of the plan", example="This is a high-speed internet plan for families."),
 *     @OA\Property(property="download_speed", type="integer", description="Download speed in Mbps", example=100),
 *     @OA\Property(property="upload_speed", type="integer", description="Upload speed in Mbps", example=50),
 *     @OA\Property(property="monthly_price", type="number", format="float", description="Monthly price of the plan", example=59.99),
 *     @OA\Property(property="overage_fee", type="number", format="float", description="Fee applied for data overages", example=15.00),
 *     @OA\Property(property="data_limit", type="integer", description="Data usage limit in GB", example=1000),
 *     @OA\Property(property="unlimited_data", type="integer", description="Flag indicating unlimited data (1: Yes, 0: No)", example=1),
 *     @OA\Property(property="plan_type", type="string", enum={"internet", "television", "telephonic", "combo"}, description="Type of plan offered", example="internet"),
 *     @OA\Property(property="modality_type", type="string", enum={"prepaid", "postpaid"}, description="Modality of the plan", example="postpaid"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, description="Status of the plan", example="active")
 * )
 *
 * @OA\Schema(
 *     schema="PlanResource",
 *     type="object",
 *     title="Plan Resource Schema",
 *     description="Representation of a service plan",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/PlanRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the plan", example=1)
 *         )
 *     }
 * )
 */
class PlanController extends Controller
{
    /**
     * @var PlanService
     */
    protected PlanService $planService;

    /**
     * Constructor.
     *
     * @param PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * Retrieve all service plans.
     *
     * @OA\Get(
     *     path="/api/plans",
     *     summary="List all service plans",
     *     tags={"Plans"},
     *     @OA\Response(
     *         response=200,
     *         description="List of service plans retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PlanResource")
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
        return PlanResource::collection($this->planService->getAll());
    }

    /**
     * Create a new service plan.
     *
     * @OA\Post(
     *     path="/api/plans",
     *     summary="Create a new service plan",
     *     tags={"Plans"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlanRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service plan created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlanResource")
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
    public function store(PlanRequest $request)
    {
        try {
            return new PlanResource($this->planService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific service plan by ID.
     *
     * @OA\Get(
     *     path="/api/plans/{id}",
     *     summary="Get a service plan by ID",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Plan ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service plan retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlanResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service plan not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return PlanResource::make($this->planService->getById($id));
    }

    /**
     * Update an existing service plan.
     *
     * @OA\Put(
     *     path="/api/plans/{id}",
     *     summary="Update a service plan",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Plan ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlanRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service plan updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlanResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service plan not found"
     *     )
     * )
     */
    public function update(PlanRequest $request, int $id)
    {
        try {
            return new PlanResource($this->planService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a service plan.
     *
     * @OA\Delete(
     *     path="/api/plans/{id}",
     *     summary="Delete a service plan",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Plan ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service plan deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service plan not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->planService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
