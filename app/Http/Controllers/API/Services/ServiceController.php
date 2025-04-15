<?php

namespace App\Http\Controllers\API\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServiceRequest;
use App\Http\Resources\Services\ServiceResource;
use App\Services\App\Models\Services\ServiceService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Services",
 *     description="API Endpoints for managing customer services"
 * )
 *
 * @OA\Schema(
 *     schema="ServiceRequest",
 *     type="object",
 *     title="Service Request Schema",
 *     description="Schema for creating or updating customer services",
 *     required={"router_id", "customer_id", "plan_id", "service_status", "service_type"},
 *     @OA\Property(property="router_id", type="integer", description="ID of the router assigned to the service", example=1),
 *     @OA\Property(property="customer_id", type="integer", description="ID of the customer associated with the service", example=10),
 *     @OA\Property(property="plan_id", type="integer", description="ID of the plan associated with the service", example=5),
 *     @OA\Property(property="service_ip", type="string", maxLength=255, description="IP address assigned to the service", example="192.168.1.100"),
 *     @OA\Property(property="service_status", type="string", enum={"active", "inactive", "suspended", "pending", "free"}, description="Current status of the service", example="active"),
 *     @OA\Property(property="service_type", type="string", enum={"ftth", "adsl", "satellite"}, description="Type of service connection", example="ftth"),
 *     @OA\Property(property="activation_date", type="string", format="date", description="Date the service was activated", example="2022-01-15"),
 *     @OA\Property(property="deactivation_date", type="string", format="date", description="Date the service was deactivated", example="2023-11-01"),
 *     @OA\Property(property="bandwidth", type="integer", description="Bandwidth allocated to the service (in Mbps)", example=100),
 *     @OA\Property(property="mac_address", type="string", maxLength=255, description="MAC address of the associated device", example="00:11:22:33:44:55"),
 *     @OA\Property(property="service_location", type="string", maxLength=255, description="Physical location of the service", example="123 Main Street, City"),
 *     @OA\Property(property="support_contact", type="string", maxLength=255, description="Contact information for technical support", example="+1 234 567 890"),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the service", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who last updated the service", example=2)
 * )
 *
 * @OA\Schema(
 *     schema="ServiceResource",
 *     type="object",
 *     title="Service Resource Schema",
 *     description="Representation of a customer service",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ServiceRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the service", example=1)
 *         )
 *     }
 * )
 */
class ServiceController extends Controller
{
    /**
     * @var ServiceService
     */
    protected ServiceService $serviceService;

    /**
     * Constructor.
     *
     * @param ServiceService $serviceService
     */
    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Retrieve all services.
     *
     * @OA\Get(
     *     path="/api/services",
     *     summary="List all services",
     *     tags={"Services"},
     *     @OA\Response(
     *         response=200,
     *         description="List of services retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ServiceResource")
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
        return ServiceResource::collection($this->serviceService->getAll());
    }

    /**
     * Create a new service.
     *
     * @OA\Post(
     *     path="/api/services",
     *     summary="Create a new service",
     *     tags={"Services"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ServiceRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
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
    public function store(ServiceRequest $request)
    {
        try {
            return new ServiceResource($this->serviceService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific service by ID.
     *
     * @OA\Get(
     *     path="/api/services/{id}",
     *     summary="Get a service by ID",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Service ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return ServiceResource::make($this->serviceService->getById($id));
    }

    /**
     * Update an existing service.
     *
     * @OA\Put(
     *     path="/api/services/{id}",
     *     summary="Update a service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Service ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ServiceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found"
     *     )
     * )
     */
    public function update(ServiceRequest $request, int $id)
    {
        try {
            return new ServiceResource($this->serviceService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a service.
     *
     * @OA\Delete(
     *     path="/api/services/{id}",
     *     summary="Delete a service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Service ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->serviceService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
