<?php

namespace App\Http\Controllers\API\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServiceActionRequest;
use App\Http\Resources\Services\ServiceActionResource;
use App\Services\App\Models\Services\ServiceActionService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="ServiceActions",
 *     description="API Endpoints for managing service actions such as installations and uninstallations"
 * )
 *
 * @OA\Schema(
 *     schema="ServiceActionRequest",
 *     type="object",
 *     title="Service Action Request Schema",
 *     description="Schema for creating or updating service actions",
 *     required={"service_id", "action_type", "action_date", "status"},
 *     @OA\Property(property="service_id", type="integer", description="ID of the related service", example=1),
 *     @OA\Property(property="action_type", type="string", enum={"installation", "uninstallation"}, description="Type of action", example="installation"),
 *     @OA\Property(property="action_date", type="string", format="date", description="Date of the service action", example="2023-11-01"),
 *     @OA\Property(property="action_notes", type="string", description="Optional notes for the action", example="Installation completed successfully."),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user performing the action", example=42),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "failed"}, description="Status of the action", example="completed"),
 *     @OA\Property(property="created_by", type="integer", description="User ID who created the action", example=5),
 *     @OA\Property(property="updated_by", type="integer", description="User ID who last updated the action", example=6)
 * )
 *
 * @OA\Schema(
 *     schema="ServiceActionResource",
 *     type="object",
 *     title="Service Action Resource Schema",
 *     description="Representation of a service action",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ServiceActionRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the service action", example=1)
 *         )
 *     }
 * )
 */
class ServiceActionController extends Controller
{
    /**
     * @var ServiceActionService
     */
    protected ServiceActionService $serviceActionService;

    /**
     * Constructor.
     *
     * @param ServiceActionService $serviceActionService
     */
    public function __construct(ServiceActionService $serviceActionService)
    {
        $this->serviceActionService = $serviceActionService;
    }

    /**
     * Retrieve all service actions.
     *
     * @OA\Get(
     *     path="/api/service-actions",
     *     summary="List all service actions",
     *     tags={"ServiceActions"},
     *     @OA\Response(
     *         response=200,
     *         description="List of service actions retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ServiceActionResource")
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
        return ServiceActionResource::collection($this->serviceActionService->getAll());
    }

    /**
     * Create a new service action.
     *
     * @OA\Post(
     *     path="/api/service-actions",
     *     summary="Create a new service action",
     *     tags={"ServiceActions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ServiceActionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service action created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceActionResource")
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
    public function store(ServiceActionRequest $request)
    {
        try {
            return new ServiceActionResource($this->serviceActionService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific service action by ID.
     *
     * @OA\Get(
     *     path="/api/service-actions/{id}",
     *     summary="Get a service action by ID",
     *     tags={"ServiceActions"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Service action ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service action retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceActionResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service action not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return ServiceActionResource::make($this->serviceActionService->getById($id));
    }

    /**
     * Update an existing service action.
     *
     * @OA\Put(
     *     path="/api/service-actions/{id}",
     *     summary="Update a service action",
     *     tags={"ServiceActions"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Service action ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ServiceActionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service action updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceActionResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service action not found"
     *     )
     * )
     */
    public function update(ServiceActionRequest $request, int $id)
    {
        try {
            return new ServiceActionResource($this->serviceActionService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a service action.
     *
     * @OA\Delete(
     *     path="/api/service-actions/{id}",
     *     summary="Delete a service action",
     *     tags={"ServiceActions"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Service action ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service action deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service action not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->serviceActionService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
