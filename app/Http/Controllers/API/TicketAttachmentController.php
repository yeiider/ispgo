<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketAttachmentResource;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Ticket Attachments",
 *     description="API Endpoints for managing ticket attachments"
 * )
 *
 * @OA\Schema(
 *     schema="TicketAttachmentRequest",
 *     type="object",
 *     title="Ticket Attachment Request Schema",
 *     description="Schema for creating or updating a ticket attachment",
 *     required={"file"},
 *     @OA\Property(property="file", type="string", format="binary", description="File to upload"),
 * )
 *
 * @OA\Schema(
 *     schema="TicketAttachmentResource",
 *     type="object",
 *     title="Ticket Attachment Resource Schema",
 *     description="Representation of a ticket attachment",
 *     @OA\Property(property="id", type="integer", description="Attachment ID", example=1),
 *     @OA\Property(property="ticket_id", type="integer", description="Ticket ID", example=1),
 *     @OA\Property(property="comment_id", type="integer", description="Comment ID (if attached to a comment)", example=null),
 *     @OA\Property(property="filename", type="string", description="Stored filename", example="abc123.pdf"),
 *     @OA\Property(property="original_filename", type="string", description="Original filename", example="document.pdf"),
 *     @OA\Property(property="file_path", type="string", description="Path to the file", example="ticket-attachments/abc123.pdf"),
 *     @OA\Property(property="url", type="string", description="Full URL to the file", example="https://example.com/storage/ticket-attachments/abc123.pdf"),
 *     @OA\Property(property="mime_type", type="string", description="MIME type of the file", example="application/pdf"),
 *     @OA\Property(property="file_size", type="integer", description="File size in bytes", example=12345),
 *     @OA\Property(property="human_file_size", type="string", description="Human-readable file size", example="12.1 KB"),
 *     @OA\Property(property="is_image", type="boolean", description="Whether the file is an image", example=false),
 *     @OA\Property(property="uploaded_by", type="integer", description="ID of the user who uploaded the file", example=1),
 *     @OA\Property(property="uploader", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2023-10-15T14:53:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2023-10-15T14:53:00Z"),
 * )
 */
class TicketAttachmentController extends Controller
{
    /**
     * Display a listing of the attachments.
     *
     * @OA\Get(
     *     path="/api/v1/attachments",
     *     summary="List all attachments",
     *     tags={"Ticket Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of attachments retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TicketAttachmentResource")
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
        return TicketAttachmentResource::collection(TicketAttachment::with('uploader')->get());
    }

    /**
     * Store a newly created attachment for a ticket.
     *
     * @OA\Post(
     *     path="/api/v1/tickets/{ticket_id}/attachments",
     *     summary="Upload a file to a ticket",
     *     tags={"Ticket Attachments"},
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/TicketAttachmentRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Attachment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketAttachmentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found"
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
    public function store(Request $request, int $ticketId)
    {
        try {
            // Validate request
            $request->validate([
                'file' => 'required|file|max:10240', // 10MB max
            ]);

            // Check if ticket exists
            $ticket = Ticket::findOrFail($ticketId);

            // Store the file
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $path = $file->store('ticket-attachments', 'public');

            // Create attachment record
            $attachment = new TicketAttachment([
                'ticket_id' => $ticketId,
                'filename' => basename($path),
                'original_filename' => $originalFilename,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            $attachment->save();

            return new TicketAttachmentResource($attachment->load('uploader'));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created attachment for a comment.
     *
     * @OA\Post(
     *     path="/api/v1/comments/{comment_id}/attachments",
     *     summary="Upload a file to a comment",
     *     tags={"Ticket Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="comment_id",
     *         description="Comment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/TicketAttachmentRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Attachment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketAttachmentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
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
    public function storeForComment(Request $request, int $commentId)
    {
        try {
            // Validate request
            $request->validate([
                'file' => 'required|file|max:10240', // 10MB max
            ]);

            // Check if comment exists
            $comment = \App\Models\TicketComment::findOrFail($commentId);

            // Store the file
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $path = $file->store('ticket-attachments', 'public');

            // Create attachment record
            $attachment = new TicketAttachment([
                'ticket_id' => $comment->ticket_id,
                'comment_id' => $commentId,
                'filename' => basename($path),
                'original_filename' => $originalFilename,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            $attachment->save();

            return new TicketAttachmentResource($attachment->load('uploader'));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified attachment.
     *
     * @OA\Get(
     *     path="/api/v1/attachments/{id}",
     *     summary="Get an attachment by ID",
     *     tags={"Ticket Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attachment retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketAttachmentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attachment not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return new TicketAttachmentResource(TicketAttachment::with('uploader')->findOrFail($id));
    }

    /**
     * Remove the specified attachment from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/attachments/{id}",
     *     summary="Delete an attachment",
     *     tags={"Ticket Attachments"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attachment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attachment not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $attachment = TicketAttachment::findOrFail($id);

            // Delete the file from storage
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            // Delete the record
            $attachment->delete();

            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
