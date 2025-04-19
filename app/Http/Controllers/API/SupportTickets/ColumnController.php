<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\ColumnRequest;
use App\Http\Resources\SupportTickets\ColumnResource;
use App\Services\SupportTickets\ColumnService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Columns",
 *     description="API Endpoints for managing board columns"
 * )
 *
 * @OA\Schema(
 *     schema="ColumnRequest",
 *     type="object",
 *     title="Column Request Schema",
 *     description="Schema for creating or updating a board's column",
 *     required={"board_id", "title"},
 *     @OA\Property(property="board_id", type="integer", description="ID of the board to which the column belongs", example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, description="Title of the column", example="To Do"),
 *     @OA\Property(property="position", type="integer", description="Position of the column in the board", example=2)
 * )
 *
 * @OA\Schema(
 *     schema="ColumnResource",
 *     type="object",
 *     title="Column Resource Schema",
 *     description="Representation of a board's column",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ColumnRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the column", example=1)
 *         )
 *     }
 * )
 */
class ColumnController extends Controller
{
    /**
     * @var ColumnService
     */
    protected ColumnService $columnService;

    /**
     * Constructor.
     *
     * @param ColumnService $columnService
     */
    public function __construct(ColumnService $columnService)
    {
        $this->columnService = $columnService;
    }

    /**
     * Retrieve all columns.
     *
     * @OA\Get(
     *     path="/api/columns",
     *     summary="List all columns",
     *     tags={"Columns"},
     *     @OA\Response(
     *         response=200,
     *         description="List of columns retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ColumnResource")
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
        return ColumnResource::collection($this->columnService->getAll());
    }

    /**
     * Create a new column.
     *
     * @OA\Post(
     *     path="/api/columns",
     *     summary="Create a new column",
     *     tags={"Columns"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ColumnRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Column created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ColumnResource")
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
    public function store(ColumnRequest $request)
    {
        try {
            return new ColumnResource($this->columnService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific column by ID.
     *
     * @OA\Get(
     *     path="/api/columns/{id}",
     *     summary="Get a column by ID",
     *     tags={"Columns"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Column ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Column retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ColumnResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Column not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return ColumnResource::make($this->columnService->getById($id));
    }

    /**
     * Update an existing column.
     *
     * @OA\Put(
     *     path="/api/columns/{id}",
     *     summary="Update a column",
     *     tags={"Columns"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Column ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ColumnRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Column updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ColumnResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Column not found"
     *     )
     * )
     */
    public function update(ColumnRequest $request, int $id)
    {
        try {
            return new ColumnResource($this->columnService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a column.
     *
     * @OA\Delete(
     *     path="/api/columns/{id}",
     *     summary="Delete a column",
     *     tags={"Columns"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Column ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Column deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Column not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->columnService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
