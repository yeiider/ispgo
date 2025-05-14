<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\TaskAttachmentRequest;
use App\Http\Resources\SupportTickets\TaskAttachmentResource;
use App\Services\SupportTickets\TaskAttachmentService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Task Attachments",
 *     description="API Endpoints for managing task attachments"
 * )
 *
 * @OA\Schema(
 *     schema="TaskAttachmentRequest",
 *     type="object",
 *     title="Task Attachment Request Schema",
 *     description="Schema for creating or updating a task attachment",
 *     required={"task_id", "file_path", "file_name", "uploaded_by"},
 *     @OA\Property(property="task_id", type="integer", description="ID of the related task", example=1),
 *     @OA\Property(property="file_path", type="string", maxLength=255, description="Path to the attached file", example="/uploads/task_1/file.pdf"),
 *     @OA\Property(property="file_name", type="string", maxLength=255, description="Name of the attached file", example="file.pdf"),
 *     @OA\Property(property="uploaded_by", type="integer", description="ID of the user who uploaded the file", example=5)
 * )
 *
 * @OA\Schema(
 *     schema="TaskAttachmentResource",
 *     type="object",
 *     title="Task Attachment Resource Schema",
 *     description="Representation of a task attachment",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TaskAttachmentRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the task attachment", example=100)
 *         )
 *     }
 * )
 */
class TaskAttachmentController extends Controller
{
    /**
     * @var TaskAttachmentService
     */
    protected TaskAttachmentService $taskAttachmentService;

    /**
     * Constructor.
     *
     * @param TaskAttachmentService $taskAttachmentService
     */
    public function __construct(TaskAttachmentService $taskAttachmentService)
    {
        $this->taskAttachmentService = $taskAttachmentService;
    }

    /**
     * Retrieve all task attachments.
     *
     * @OA\Get(
     *     path="/api/v1/task-attachments",
     *     summary="List all task attachments",
     *     tags={"Task Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of task attachments retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskAttachmentResource")
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
        return TaskAttachmentResource::collection($this->taskAttachmentService->getAll());
    }

    /**
     * Create a new task attachment.
     *
     * @OA\Post(
     *     path="/api/v1/task-attachments",
     *     summary="Create a new task attachment",
     *     tags={"Task Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskAttachmentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task attachment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskAttachmentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(TaskAttachmentRequest $request)
    {
        try {
            return new TaskAttachmentResource($this->taskAttachmentService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific task attachment by ID.
     *
     * @OA\Get(
     *     path="/api/v1/task-attachments/{id}",
     *     summary="Get a task attachment by ID",
     *     tags={"Task Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task attachment retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskAttachmentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task attachment not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return TaskAttachmentResource::make($this->taskAttachmentService->getById($id));
    }

    /**
     * Update an existing task attachment.
     *
     * @OA\Put(
     *     path="/api/v1/task-attachments/{id}",
     *     summary="Update a task attachment",
     *     tags={"Task Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskAttachmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task attachment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskAttachmentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task attachment not found"
     *     )
     * )
     */
    public function update(TaskAttachmentRequest $request, int $id)
    {
        try {
            return new TaskAttachmentResource($this->taskAttachmentService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a task attachment.
     *
     * @OA\Delete(
     *     path="/api/v1/task-attachments/{id}",
     *     summary="Delete a task attachment",
     *     tags={"Task Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Task attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task attachment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task attachment not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->taskAttachmentService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
