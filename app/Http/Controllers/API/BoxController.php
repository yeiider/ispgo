<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoxRequest;
use App\Http\Resources\BoxResource;
use App\Services\BoxService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Boxes",
 *     description="API Endpoints for managing boxes"
 * )
 *
 * @OA\Schema(
 *     schema="BoxRequest",
 *     type="object",
 *     title="Box Request Schema",
 *     description="Schema for creating or updating a box",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", maxLength=255, description="Name of the box", example="Example Box"),
 *     @OA\Property(property="users", type="string", maxLength=255, description="Associated users in the box", example="[1,2,3]")
 * )
 *
 * @OA\Schema(
 *     schema="BoxResource",
 *     type="object",
 *     title="Box Resource Schema",
 *     description="Representation of a box",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/BoxRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the box", example=1)
 *         )
 *     }
 * )
 */
class BoxController extends Controller
{
    /**
     * @var BoxService
     */
    protected BoxService $boxService;

    /**
     * Constructor.
     *
     * @param BoxService $boxService
     */
    public function __construct(BoxService $boxService)
    {
        $this->boxService = $boxService;
    }

    /**
     * Retrieve all boxes.
     *
     * @OA\Get(
     *     path="/api/boxes",
     *     summary="List all boxes",
     *     tags={"Boxes"},
     *     @OA\Response(
     *         response=200,
     *         description="List of boxes retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BoxResource")
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
        return BoxResource::collection($this->boxService->getAll());
    }

    /**
     * Create a new box.
     *
     * @OA\Post(
     *     path="/api/boxes",
     *     summary="Create a new box",
     *     tags={"Boxes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BoxRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Box created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BoxResource")
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
    public function store(BoxRequest $request)
    {
        try {
            return new BoxResource($this->boxService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific box by ID.
     *
     * @OA\Get(
     *     path="/api/boxes/{id}",
     *     summary="Get a box by ID",
     *     tags={"Boxes"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Box ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Box retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BoxResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Box not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return BoxResource::make($this->boxService->getById($id));
    }

    /**
     * Update an existing box.
     *
     * @OA\Put(
     *     path="/api/boxes/{id}",
     *     summary="Update a box",
     *     tags={"Boxes"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Box ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BoxRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Box updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BoxResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Box not found"
     *     )
     * )
     */
    public function update(BoxRequest $request, int $id)
    {
        try {
            return new BoxResource($this->boxService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a box.
     *
     * @OA\Delete(
     *     path="/api/boxes/{id}",
     *     summary="Delete a box",
     *     tags={"Boxes"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Box ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Box deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Box not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->boxService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
