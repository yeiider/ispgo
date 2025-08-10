<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketAttachmentResource;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
     *             @OA\Schema(
     *                 type="object",
     *                 required={"file"},
     *                 @OA\Property(property="file", type="string", format="binary", description="Archivo a subir"),
     *                 @OA\Property(property="original_filename", type="string", description="Nombre original del archivo (opcional)")
     *             )
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"file_base64"},
     *                 @OA\Property(property="file_base64", type="string", description="Contenido del archivo en base64, puede incluir data URI"),
     *                 @OA\Property(property="original_filename", type="string", description="Nombre original del archivo (opcional)"),
     *                 @OA\Property(property="mime_type", type="string", description="MIME type del archivo (opcional)")
     *             )
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
            $request->validate([
                'file' => 'required_without:file_base64|file|max:10240',
                'file_base64' => 'required_without:file|nullable|string',
                'original_filename' => 'nullable|string|max:255',
                'mime_type' => 'nullable|string|max:255',
            ]);

            Ticket::findOrFail($ticketId);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalFilename = $request->input('original_filename', $file->getClientOriginalName());
                $storedPath = $file->store('ticket-attachments', 'public');
                $storedFilename = basename($storedPath);
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
            } elseif ($request->filled('file_base64')) {
                $base64 = $request->input('file_base64');
                $dataPart = $base64;
                $providedMime = $request->input('mime_type');

                if (str_contains($base64, ',')) {
                    [$meta, $dataPart] = explode(',', $base64, 2);
                    if (!$providedMime && str_starts_with($meta, 'data:') && str_contains($meta, ';base64')) {
                        $providedMime = trim(str_replace(['data:', ';base64'], '', $meta));
                    }
                }

                $binary = base64_decode($dataPart, true);
                if ($binary === false) {
                    return response()->json(['error' => 'Archivo base64 inválido.'], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $mimeType = $providedMime ?: (function ($bin) {
                    if (class_exists(\finfo::class)) {
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $detected = $finfo->buffer($bin);
                        return $detected ?: 'application/octet-stream';
                    }
                    return 'application/octet-stream';
                })($binary);

                $mimeToExt = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    'application/pdf' => 'pdf',
                ];
                $ext = $mimeToExt[$mimeType] ?? 'bin';

                $storedFilename = (string) Str::uuid() . '.' . $ext;
                $storedPath = 'ticket-attachments/' . $storedFilename;

                Storage::disk('public')->put($storedPath, $binary);

                $fileSize = strlen($binary);
                $originalFilename = $request->input('original_filename', $storedFilename);
            } else {
                return response()->json(['error' => 'Se requiere "file" o "file_base64".'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $attachment = new TicketAttachment([
                'ticket_id' => $ticketId,
                'filename' => $storedFilename,
                'original_filename' => $originalFilename,
                'file_path' => $storedPath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'uploaded_by' => auth()->id(),
            ]);

            $attachment->save();

            return (new TicketAttachmentResource($attachment->load('uploader')))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
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
     *             @OA\Schema(
     *                 type="object",
     *                 required={"file"},
     *                 @OA\Property(property="file", type="string", format="binary", description="Archivo a subir"),
     *                 @OA\Property(property="original_filename", type="string", description="Nombre original del archivo (opcional)")
     *             )
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"file_base64"},
     *                 @OA\Property(property="file_base64", type="string", description="Contenido del archivo en base64, puede incluir data URI"),
     *                 @OA\Property(property="original_filename", type="string", description="Nombre original del archivo (opcional)"),
     *                 @OA\Property(property="mime_type", type="string", description="MIME type del archivo (opcional)")
     *             )
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
            // Validación flexible: archivo subido o contenido base64 en JSON
            $request->validate([
                'file' => 'required_without:file_base64|file|max:10240',
                'file_base64' => 'required_without:file|nullable|string',
                'original_filename' => 'nullable|string|max:255',
                'mime_type' => 'nullable|string|max:255',
            ]);

            // Verifica existencia del comentario
            $comment = \App\Models\TicketComment::findOrFail($commentId);

            $storedPath = null;
            $storedFilename = null;
            $originalFilename = null;
            $mimeType = null;
            $fileSize = null;

            if ($request->hasFile('file')) {
                // Flujo multipart/form-data
                $file = $request->file('file');
                $originalFilename = $request->input('original_filename', $file->getClientOriginalName());
                $storedPath = $file->store('ticket-attachments', 'public');
                $storedFilename = basename($storedPath);
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
            } elseif ($request->filled('file_base64')) {
                // Flujo JSON con base64
                $base64 = $request->input('file_base64');
                $dataPart = $base64;
                $providedMime = $request->input('mime_type');

                if (str_contains($base64, ',')) {
                    [$meta, $dataPart] = explode(',', $base64, 2);
                    if (!$providedMime && str_starts_with($meta, 'data:') && str_contains($meta, ';base64')) {
                        $providedMime = trim(str_replace(['data:', ';base64'], '', $meta));
                    }
                }

                $binary = base64_decode($dataPart, true);
                if ($binary === false) {
                    return response()->json([
                        'error' => 'Archivo base64 inválido.'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $mimeType = $providedMime ?: (function ($bin) {
                    if (class_exists(\finfo::class)) {
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $detected = $finfo->buffer($bin);
                        return $detected ?: 'application/octet-stream';
                    }
                    return 'application/octet-stream';
                })($binary);

                $mimeToExt = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    'application/pdf' => 'pdf',
                ];
                $ext = $mimeToExt[$mimeType] ?? 'bin';

                $storedFilename = Str::uuid() . '.' . $ext;
                $storedPath = 'ticket-attachments/' . $storedFilename;

                Storage::disk('public')->put($storedPath, $binary);

                $fileSize = strlen($binary);
                $originalFilename = $request->input('original_filename', $storedFilename);
            } else {
                return response()->json([
                    'error' => 'Se requiere "file" o "file_base64".'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Crear registro de adjunto
            $attachment = new TicketAttachment([
                'ticket_id' => $comment->ticket_id,
                'comment_id' => $commentId,
                'filename' => $storedFilename,
                'original_filename' => $originalFilename,
                'file_path' => $storedPath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
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
