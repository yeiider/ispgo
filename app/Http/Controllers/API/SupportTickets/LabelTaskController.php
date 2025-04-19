<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\LabelTaskRequest;
use App\Http\Resources\SupportTickets\LabelTaskResource;
use App\Services\SupportTickets\LabelTaskService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="LabelTasks",
 *     description="API Endpoints for managing label-task relationships"
 * )
 *
 * @OA\Schema(
 *     schema="LabelTaskRequest",
 *     type="object",
 *     title="LabelTask Request Schema",
 *     description="Schema for creating or updating a label-task relationship",
 *     required={"label_id", "task_id"},
 *     @OA\Property(property="label_id", type="integer", description="ID of the label", example=1),
 *     @OA\Property(property="task_id", type="integer", description="ID of the task", example=101)
 * )
 *
 * @OA\Schema(
 *     schema="LabelTaskResource",
 *     type="object",
 *     title="LabelTask Resource Schema",
 *     description="Representation of a label-task relationship",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/LabelTaskRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the label-task relationship", example=10)
 *         )
 *     }
 * )
 */
class LabelTaskController extends Controller
{
    /**
     * @var LabelTaskService
     */
    protected LabelTaskService $labelTaskService;

    /**
     * Constructor.
     *
     * @param LabelTaskService $labelTaskService
     */
    public function __construct(LabelTaskService $labelTaskService)
    {
        $this->labelTaskService = $labelTaskService;
    }

    /**
     * Retrieve all label-task relationships.
     *
     * @OA\Get(
     *     path="/api/label-tasks",
     *     summary="List all label-task relationships",
     *     tags={"LabelTasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of label-task relationships retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LabelTaskResource")
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
        return LabelTaskResource::collection($this->labelTaskService->getAll());
    }

    /**
     * Create a new label-task relationship.
     *
     * @OA\Post(
     *     path="/api/label-tasks",
     *     summary="Create a new label-task relationship",
     *     tags={"LabelTasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LabelTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Label-task relationship created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabelTaskResource")
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
    public function store(LabelTaskRequest $request)
    {
        try {
            return new LabelTaskResource($this->labelTaskService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific label-task relationship by ID.
     *
     * @OA\Get(
     *     path="/api/label-tasks/{id}",
     *     summary="Get a label-task relationship by ID",
     *     tags={"LabelTasks"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Label-task relationship ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Label-task relationship retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabelTaskResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Label-task relationship not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return LabelTaskResource::make($this->labelTaskService->getById($id));
    }

    /**
     * Update an existing label-task relationship.
     *
     * @OA\Put(
     *     path="/api/label-tasks/{id}",
     *     summary="Update a label-task relationship",
     *     tags={"LabelTasks"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Label-task relationship ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LabelTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Label-task relationship updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabelTaskResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Label-task relationship not found"
     *     )
     * )
     */
    public function update(LabelTaskRequest $request, int $id)
    {
        try {
            return new LabelTaskResource($this->labelTaskService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a label-task relationship.
     *
     * @OA\Delete(
     *     path="/api/label-tasks/{id}",
     *     summary="Delete a label-task relationship",
     *     tags={"LabelTasks"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Label-task relationship ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Label-task relationship deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Label-task relationship not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->labelTaskService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
