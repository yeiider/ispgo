<?php

namespace App\Http\Controllers\API\SupportTickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTickets\BoardRequest;
use App\Http\Resources\SupportTickets\BoardResource;
use App\Services\App\Models\SupportTickets\BoardService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Boards",
 *     description="API Endpoints for managing support ticket boards"
 * )
 *
 * @OA\Schema(
 *     schema="BoardRequest",
 *     type="object",
 *     title="Board Request Schema",
 *     description="Schema for creating or updating ticket boards",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", maxLength=255, description="Name of the board", example="Technical Support Board"),
 *     @OA\Property(property="description", type="string", description="Description of the board", example="Handles technical issues and resolutions."),
 *     @OA\Property(property="created_by", type="integer", description="User ID who created the board", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="User ID who last updated the board", example=2)
 * )
 *
 * @OA\Schema(
 *     schema="BoardResource",
 *     type="object",
 *     title="Board Resource Schema",
 *     description="Representation of a ticket board",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/BoardRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the board", example=1)
 *         )
 *     }
 * )
 */
class BoardController extends Controller
{
    /**
     * @var BoardService
     */
    protected BoardService $boardService;

    /**
     * Constructor.
     *
     * @param BoardService $boardService
     */
    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    /**
     * Retrieve all boards.
     *
     * @OA\Get(
     *     path="/api/boards",
     *     summary="List all boards",
     *     tags={"Boards"},
     *     @OA\Response(
     *         response=200,
     *         description="List of boards retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BoardResource")
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
        return BoardResource::collection($this->boardService->getAll());
    }

    /**
     * Create a new board.
     *
     * @OA\Post(
     *     path="/api/boards",
     *     summary="Create a new board",
     *     tags={"Boards"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BoardRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Board created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BoardResource")
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
    public function store(BoardRequest $request)
    {
        try {
            return new BoardResource($this->boardService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific board by ID.
     *
     * @OA\Get(
     *     path="/api/boards/{id}",
     *     summary="Get a board by ID",
     *     tags={"Boards"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Board ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Board retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BoardResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Board not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return BoardResource::make($this->boardService->getById($id));
    }

    /**
     * Update an existing board.
     *
     * @OA\Put(
     *     path="/api/boards/{id}",
     *     summary="Update a board",
     *     tags={"Boards"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Board ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BoardRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Board updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BoardResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Board not found"
     *     )
     * )
     */
    public function update(BoardRequest $request, int $id)
    {
        try {
            return new BoardResource($this->boardService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a board.
     *
     * @OA\Delete(
     *     path="/api/boards/{id}",
     *     summary="Delete a board",
     *     tags={"Boards"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Board ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Board deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Board not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->boardService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
