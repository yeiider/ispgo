<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Tickets",
 *     description="API Endpoints for managing tickets"
 * )
 *
 * @OA\Schema(
 *     schema="TicketRequest",
 *     type="object",
 *     title="Ticket Request Schema",
 *     description="Schema for creating or updating a ticket",
 *     required={"customer_id", "service_id", "title", "issue_type"},
 *     @OA\Property(property="customer_id", type="integer", description="Customer ID related to the ticket", example=1),
 *     @OA\Property(property="service_id", type="integer", description="Service ID related to the ticket", example=42),
 *     @OA\Property(property="issue_type", type="string", description="Type of issue reported", example="Technical Issue"),
 *     @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "urgent"}, description="Priority of the ticket", example="high"),
 *     @OA\Property(property="status", type="string", enum={"open", "in_progress", "resolved", "closed"}, description="Status of the ticket", example="open"),
 *     @OA\Property(property="title", type="string", description="Title of the ticket", example="Issue with software installation"),
 *     @OA\Property(property="description", type="string", description="Detailed description of the issue", example="The software fails to install on Windows 11."),
 *     @OA\Property(property="closed_at", type="string", format="date-time", description="Timestamp when the ticket was closed", example="2023-10-15T14:53:00Z"),
 *     @OA\Property(property="user_id", type="integer", description="User ID assigned to the ticket", example=7),
 *     @OA\Property(property="resolution_notes", type="string", description="Resolution notes added by the team", example="Issue fixed by updating the software version."),
 *     @OA\Property(property="attachments", type="string", description="Attachment file paths, if any", example="['attachment1.png', 'attachment2.pdf']"),
 *     @OA\Property(property="contact_method", type="string", description="Preferred contact method for this ticket", example="Email"),
 * )
 *
 * @OA\Schema(
 *     schema="TicketResource",
 *     type="object",
 *     title="Ticket Resource Schema",
 *     description="Representation of a ticket",
 *     @OA\Property(property="id", type="integer", description="Ticket ID", example=10),
 *     @OA\Property(property="customer_id", type="integer", description="Customer ID", example=1),
 *     @OA\Property(property="service_id", type="integer", description="Service ID", example=42),
 *     @OA\Property(property="issue_type", type="string", description="Type of issue", example="Technical Issue"),
 *     @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "urgent"}, description="Priority of the ticket", example="high"),
 *     @OA\Property(property="status", type="string", enum={"open", "in_progress", "resolved", "closed"}, description="Status of the ticket", example="open"),
 *     @OA\Property(property="title", type="string", description="Ticket title", example="Issue with software installation"),
 *     @OA\Property(property="description", type="string", description="Description of the issue", example="The software fails to install on Windows 11."),
 *     @OA\Property(property="closed_at", type="string", format="date-time", description="Timestamp of closure", example="2023-10-15T14:53:00Z"),
 *     @OA\Property(property="user_id", type="integer", description="Assigned user ID", example=7),
 *     @OA\Property(property="resolution_notes", type="string", description="Resolution notes", example="Issue fixed by updating the software version."),
 *     @OA\Property(property="attachments", type="array", @OA\Items(type="string"), description="Array of file paths for attachments", example="['attachment1.png', 'attachment2.pdf']"),
 *     @OA\Property(property="contact_method", type="string", description="Preferred contact method", example="Email"),
 * )
 */
class TicketController extends Controller
{
    /**
     * @var TicketService
     */
    protected TicketService $ticketService;

    /**
     * DummyModel Constructor
     *
     * @param TicketService $ticketService
     *
     */
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Retrieve all tickets.
     *
     * @OA\Get(
     *     path="/api/tickets",
     *     summary="List all tickets",
     *     tags={"Tickets"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tickets retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TicketResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return TicketResource::collection($this->ticketService->getAll());
    }

    /**
     * Create a new ticket.
     *
     * @OA\Post(
     *     path="/api/tickets",
     *     summary="Create a new ticket",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TicketRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ticket created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(TicketRequest $request): TicketResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new TicketResource($this->ticketService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a ticket by ID.
     *
     * @OA\Get(
     *     path="/api/tickets/{id}",
     *     summary="Get a ticket by ID",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Ticket ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found"
     *     )
     * )
     */
    public function show(int $id): TicketResource
    {
        return TicketResource::make($this->ticketService->getById($id));
    }

    /**
     * Update an existing ticket.
     *
     * @OA\Put(
     *     path="/api/tickets/{id}",
     *     summary="Update a ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Ticket ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TicketRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TicketResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(TicketRequest $request, int $id): TicketResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new TicketResource($this->ticketService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a ticket.
     *
     * @OA\Delete(
     *     path="/api/tickets/{id}",
     *     summary="Delete a ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Ticket ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket deleted successfully",
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
            $this->ticketService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
