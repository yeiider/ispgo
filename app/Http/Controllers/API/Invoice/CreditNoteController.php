<?php

namespace App\Http\Controllers\API\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreditNoteRequest;
use App\Http\Resources\Invoice\CreditNoteResource;
use App\Services\Invoice\CreditNoteService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="CreditNotes",
 *     description="API Endpoints for managing credit notes"
 * )
 *
 * @OA\Schema(
 *     schema="CreditNoteRequest",
 *     type="object",
 *     title="Credit Note Request Schema",
 *     description="Schema for creating or updating credit notes",
 *     required={"invoice_id", "user_id", "amount", "issue_date", "reason"},
 *     @OA\Property(property="invoice_id", type="integer", description="ID of the related invoice", example=5),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user associated with the credit note", example=2),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the credit note", example=150.50),
 *     @OA\Property(property="issue_date", type="string", format="date", description="Issue date of the credit note", example="2023-10-15"),
 *     @OA\Property(property="reason", type="string", description="Reason for issuing the credit note", example="Refund for overbilling"),
 * )
 *
 * @OA\Schema(
 *     schema="CreditNoteResource",
 *     type="object",
 *     title="Credit Note Resource Schema",
 *     description="Representation of a credit note",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the credit note", example=1),
 *     @OA\Property(property="invoice_id", type="integer", description="ID of the related invoice", example=5),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user associated with the credit note", example=2),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the credit note", example=150.50),
 *     @OA\Property(property="issue_date", type="string", format="date", description="Issue date of the credit note", example="2023-10-15"),
 *     @OA\Property(property="reason", type="string", description="Reason for issuing the credit note", example="Refund for overbilling"),
 * )
 */
class CreditNoteController extends Controller
{
    /**
     * @var CreditNoteService
     */
    protected CreditNoteService $creditNoteService;

    /**
     * Constructor.
     *
     * @param CreditNoteService $creditNoteService
     */
    public function __construct(CreditNoteService $creditNoteService)
    {
        $this->creditNoteService = $creditNoteService;
    }

    /**
     * Retrieve all credit notes.
     *
     * @OA\Get(
     *     path="/api/credit-notes",
     *     summary="List all credit notes",
     *     tags={"CreditNotes"},
     *     @OA\Response(
     *         response=200,
     *         description="List of credit notes retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CreditNoteResource")
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
        return CreditNoteResource::collection($this->creditNoteService->getAll());
    }

    /**
     * Create a new credit note.
     *
     * @OA\Post(
     *     path="/api/credit-notes",
     *     summary="Create a new credit note",
     *     tags={"CreditNotes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreditNoteRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Credit note created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CreditNoteResource")
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
    public function store(CreditNoteRequest $request)
    {
        try {
            return new CreditNoteResource($this->creditNoteService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific credit note by ID.
     *
     * @OA\Get(
     *     path="/api/credit-notes/{id}",
     *     summary="Get a credit note by ID",
     *     tags={"CreditNotes"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Credit note ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Credit note retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CreditNoteResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credit note not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return CreditNoteResource::make($this->creditNoteService->getById($id));
    }

    /**
     * Update an existing credit note.
     *
     * @OA\Put(
     *     path="/api/credit-notes/{id}",
     *     summary="Update a credit note",
     *     tags={"CreditNotes"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Credit note ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreditNoteRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Credit note updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CreditNoteResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credit note not found"
     *     )
     * )
     */
    public function update(CreditNoteRequest $request, int $id)
    {
        try {
            return new CreditNoteResource($this->creditNoteService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a credit note.
     *
     * @OA\Delete(
     *     path="/api/credit-notes/{id}",
     *     summary="Delete a credit note",
     *     tags={"CreditNotes"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Credit note ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Credit note deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credit note not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->creditNoteService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
