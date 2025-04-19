<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\TaskRequest;
use App\Http\Resources\SupportTickets\TaskResource;
use App\Services\SupportTickets\TaskService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="API Endpoints for managing tasks"
 * )
 *
 * @OA\Schema(
 *     schema="TaskRequest",
 *     type="object",
 *     title="Task Request Schema",
 *     description="Schema for creating or updating a task",
 *     required={"column_id", "title", "priority"},
 *     @OA\Property(property="column_id", type="integer", description="ID of the column where the task belongs", example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, description="Title of the task", example="Fix login bug"),
 *     @OA\Property(property="description", type="string", description="Description of the task", example="Details about the issue."),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the task", example=10),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who last updated the task", example=15),
 *     @OA\Property(property="customer_id", type="integer", description="ID of the customer related to the task", example=5),
 *     @OA\Property(property="service_id", type="integer", description="ID of the associated service", example=3),
 *     @OA\Property(property="due_date", type="string", format="date", description="Due date for the task", example="2023-12-31"),
 *     @OA\Property(property="priority", type="string", maxLength=255, description="Priority of the task (e.g., High, Medium, Low)", example="High")
 * )
 *
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *     title="Task Resource Schema",
 *     description="Representation of a task",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TaskRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the task", example=100)
 *         )
 *     }
 * )
 */
class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    protected TaskService $taskService;

    /**
     * Constructor.
     *
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Retrieve all tasks.
     *
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskResource")
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
        return TaskResource::collection($this->taskService->getAll());
    }

    /**
     * Create a new task.
     *
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
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
    public function store(TaskRequest $request)
    {
        try {
            return new TaskResource($this->taskService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific task by ID.
     *
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get a task by ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TaskResource::make($this->taskService->getById($id));
    }

    /**
     * Update an existing task.
     *
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function update(TaskRequest $request, int $id)
    {
        try {
            return new TaskResource($this->taskService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a task.
     *
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->taskService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
