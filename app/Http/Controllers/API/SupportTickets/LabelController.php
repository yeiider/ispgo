<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\LabelRequest;
use App\Http\Resources\SupportTickets\LabelResource;
use App\Services\SupportTickets\LabelService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Labels",
 *     description="API Endpoints for managing ticket labels"
 * )
 *
 * @OA\Schema(
 *     schema="LabelRequest",
 *     type="object",
 *     title="Label Request Schema",
 *     description="Schema for creating or updating a label",
 *     required={"name", "color"},
 *     @OA\Property(property="name", type="string", maxLength=255, description="Name of the label", example="Urgent"),
 *     @OA\Property(property="color", type="string", maxLength=255, description="Color code of the label", example="#FF0000")
 * )
 *
 * @OA\Schema(
 *     schema="LabelResource",
 *     type="object",
 *     title="Label Resource Schema",
 *     description="Representation of a label",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/LabelRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the label", example=1)
 *         )
 *     }
 * )
 */
class LabelController extends Controller
{
    /**
     * @var LabelService
     */
    protected LabelService $labelService;

    /**
     * Constructor.
     *
     * @param LabelService $labelService
     */
    public function __construct(LabelService $labelService)
    {
        $this->labelService = $labelService;
    }

    /**
     * Retrieve all labels.
     *
     * @OA\Get(
     *     path="/api/labels",
     *     summary="List all labels",
     *     tags={"Labels"},
     *     @OA\Response(
     *         response=200,
     *         description="List of labels retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LabelResource")
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
        return LabelResource::collection($this->labelService->getAll());
    }

    /**
     * Create a new label.
     *
     * @OA\Post(
     *     path="/api/labels",
     *     summary="Create a new label",
     *     tags={"Labels"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LabelRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Label created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabelResource")
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
    public function store(LabelRequest $request)
    {
        try {
            return new LabelResource($this->labelService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific label by ID.
     *
     * @OA\Get(
     *     path="/api/labels/{id}",
     *     summary="Get a label by ID",
     *     tags={"Labels"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Label ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Label retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabelResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Label not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return LabelResource::make($this->labelService->getById($id));
    }

    /**
     * Update an existing label.
     *
     * @OA\Put(
     *     path="/api/labels/{id}",
     *     summary="Update a label",
     *     tags={"Labels"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Label ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LabelRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Label updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabelResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Label not found"
     *     )
     * )
     */
    public function update(LabelRequest $request, int $id)
    {
        try {
            return new LabelResource($this->labelService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a label.
     *
     * @OA\Delete(
     *     path="/api/labels/{id}",
     *     summary="Delete a label",
     *     tags={"Labels"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Label ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Label deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Label not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->labelService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
