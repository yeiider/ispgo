<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketCommentRequest;
use App\Http\Resources\TicketCommentResource;
use App\Services\TicketCommentService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Ticket Comments",
 *     description="API Endpoints for managing ticket comments"
 * )
 *
 * @OA\Schema(
 *     schema="TicketCommentRequest",
 *     type="object",
 *     title="Ticket Comment Request Schema",
 *     description="Schema for creating or updating a ticket comment",
 *     required={"ticket_id", "content"},
 *     @OA\Property(property="ticket_id", type="integer", description="Ticket ID related to the comment", example=1),
 *     @OA\Property(property="content", type="string", description="Content of the comment", example="This is a comment on the ticket"),
 *     @OA\Property(property="recipient_id", type="integer", description="User ID of the recipient (optional)", example=2),
 *     @OA\Property(property="attachments", type="array", @OA\Items(type="string", format="binary"), description="Files to attach to the comment"),
 * )
 *
 * @OA\Schema(
 *     schema="TicketCommentResource",
 *     type="object",
 *     title="Ticket Comment Resource Schema",
 *     description="Representation of a ticket comment",
 *     @OA\Property(property="id", type="integer", description="Comment ID", example=1),
 *     @OA\Property(property="ticket_id", type="integer", description="Ticket ID", example=1),
 *     @OA\Property(property="user_id", type="integer", description="User ID who created the comment", example=1),
 *     @OA\Property(property="user", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *     ),
 *     @OA\Property(property="content", type="string", description="Content of the comment", example="This is a comment on the ticket"),
 *     @OA\Property(property="recipient_id", type="integer", description="User ID of the recipient", example=2),
 *     @OA\Property(property="recipient", type="object",
 *         @OA\Property(property="id", type="integer", example=2),
 *         @OA\Property(property="name", type="string", example="Jane Smith"),
 *     ),
 *     @OA\Property(property="attachments", type="array", @OA\Items(ref="#/components/schemas/TicketAttachmentResource")),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2023-10-15T14:53:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2023-10-15T14:53:00Z"),
 * )
 */
class TicketCommentController extends Controller
{
    /**
     * @var TicketCommentService
     */
    protected TicketCommentService $ticketCommentService;

    /**
     * TicketCommentController Constructor
     *
     * @param TicketCommentService $ticketCommentService
     */
    public function __construct(TicketCommentService $ticketCommentService)
    {
        $this->ticketCommentService = $ticketCommentService;
    }

    /**
     * Get all comments for a ticket.
     *
     * @OA\Get(
     *     path="/api/v1/tickets/{ticket_id}/comments",
     *     summary="List all comments for a ticket",
     *     tags={"Ticket Comments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="ticket_id",
     *         description="Ticket ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of comments retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TicketCommentResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found"
     *     )
     * )
     */
    public function index(int $ticketId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return TicketCommentResource::collection($this->ticketCommentService->getByTicketId($ticketId));
    }

    /**
     * Create a new comment for a ticket.
     *
     * @OA\Post(
     *     path="/api/v1/tickets/{ticket_id}/comments",
     *     summary="Create a new comment for a ticket",
     *     tags={"Ticket Comments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="ticket_id",
     *         description="Ticket ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TicketCommentRequest"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketCommentResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function  store(TicketCommentRequest $request, int $ticketId): TicketCommentResource|\Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();

            $data['ticket_id'] = $ticketId;
            $data['user_id'] = auth()->id();

            $comment = $this->ticketCommentService->save($data);

            return new TicketCommentResource($comment);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a specific comment.
     *
     * @OA\Get(
     *     path="/api/v1/comments/{id}",
     *     summary="Get a comment by ID",
     *     tags={"Ticket Comments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketCommentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function show(int $id): TicketCommentResource
    {
        return new TicketCommentResource($this->ticketCommentService->getById($id));
    }

    /**
     * Update a comment.
     *
     * @OA\Put(
     *     path="/api/v1/comments/{id}",
     *     summary="Update a comment",
     *     tags={"Ticket Comments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TicketCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketCommentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(TicketCommentRequest $request, int $id): TicketCommentResource|\Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();
            return new TicketCommentResource($this->ticketCommentService->update($data, $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a comment.
     *
     * @OA\Delete(
     *     path="/api/v1/comments/{id}",
     *     summary="Delete a comment",
     *     tags={"Ticket Comments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->ticketCommentService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
