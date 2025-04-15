<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\TaskCommentRequest;
use App\Http\Resources\SupportTickets\TaskCommentResource;
use App\Services\App\Models\SupportTickets\TaskCommentService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Task Comments",
 *     description="API Endpoints for managing task comments"
 * )
 *
 * @OA\Schema(
 *     schema="TaskCommentRequest",
 *     type="object",
 *     title="Task Comment Request Schema",
 *     description="Schema for creating or updating a task comment",
 *     required={"task_id", "user_id", "content"},
 *     @OA\Property(property="task_id", type="integer", description="ID of the task", example=1),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user who made the comment", example=5),
 *     @OA\Property(property="content", type="string", description="Content of the comment", example="This is a comment on the task.")
 * )
 *
 * @OA\Schema(
 *     schema="TaskCommentResource",
 *     type="object",
 *     title="Task Comment Resource Schema",
 *     description="Representation of a task comment",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TaskCommentRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the comment", example=10)
 *         )
 *     }
 * )
 */
class TaskCommentController extends Controller
{
    /**
     * @var TaskCommentService
     */
    protected TaskCommentService $taskCommentService;

    /**
     * Constructor.
     *
     * @param TaskCommentService $taskCommentService
     */
    public function __construct(TaskCommentService $taskCommentService)
    {
        $this->taskCommentService = $taskCommentService;
    }

    /**
     * Retrieve all task comments.
     *
     * @OA\Get(
     *     path="/api/task-comments",
     *     summary="List all task comments",
     *     tags={"Task Comments"},
     *     @OA\Response(
     *         response=200,
     *         description="List of task comments retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskCommentResource")
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
        return TaskCommentResource::collection($this->taskCommentService->getAll());
    }

    /**
     * Create a new task comment.
     *
     * @OA\Post(
     *     path="/api/task-comments",
     *     summary="Create a new task comment",
     *     tags={"Task Comments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task comment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCommentResource")
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
    public function store(TaskCommentRequest $request)
    {
        try {
            return new TaskCommentResource($this->taskCommentService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific task comment by ID.
     *
     * @OA\Get(
     *     path="/api/task-comments/{id}",
     *     summary="Get a task comment by ID",
     *     tags={"Task Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task comment retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCommentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task comment not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TaskCommentResource::make($this->taskCommentService->getById($id));
    }

    /**
     * Update an existing task comment.
     *
     * @OA\Put(
     *     path="/api/task-comments/{id}",
     *     summary="Update a task comment",
     *     tags={"Task Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task comment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCommentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task comment not found"
     *     )
     * )
     */
    public function update(TaskCommentRequest $request, int $id)
    {
        try {
            return new TaskCommentResource($this->taskCommentService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a task comment.
     *
     * @OA\Delete(
     *     path="/api/task-comments/{id}",
     *     summary="Delete a task comment",
     *     tags={"Task Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task comment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task comment not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->taskCommentService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
