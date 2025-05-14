<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailyBoxRequest;
use App\Http\Resources\DailyBoxResource;
use App\Services\DailyBoxService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="DailyBoxes",
 *     description="API Endpoints for managing Daily Boxes"
 * )
 *
 * @OA\Schema(
 *     schema="DailyBoxRequest",
 *     type="object",
 *     title="Daily Box Request Schema",
 *     description="Schema for creating or updating a Daily Box",
 *     required={"box_id", "date", "start_amount"},
 *     @OA\Property(property="box_id", type="integer", description="ID of the box related to the daily record", example=1),
 *     @OA\Property(property="date", type="string", format="date", description="Date of the daily box record", example="2023-11-01"),
 *     @OA\Property(property="start_amount", type="number", format="float", description="Start amount in the box", example=1000.50),
 *     @OA\Property(property="end_amount", type="number", format="float", description="End amount in the box", example=5000.00)
 * )
 *
 * @OA\Schema(
 *     schema="DailyBoxResource",
 *     type="object",
 *     title="Daily Box Resource Schema",
 *     description="Represents a Daily Box entry",
 *     @OA\Property(property="box_id", type="integer", description="ID of the box related to the daily record", example=1),
 *     @OA\Property(property="date", type="string", format="date", description="Date of the daily box record", example="2023-11-01"),
 *     @OA\Property(property="start_amount", type="number", format="float", description="Start amount in the box", example=1000.50),
 *     @OA\Property(property="end_amount", type="number", format="float", description="End amount in the box", example=5000.00)
 * )
 */
class DailyBoxController extends Controller
{
    /**
     * @var DailyBoxService
     */
    protected DailyBoxService $dailyBoxService;

    /**
     * DummyModel Constructor
     *
     * @param DailyBoxService $dailyBoxService
     *
     */
    public function __construct(DailyBoxService $dailyBoxService)
    {
        $this->dailyBoxService = $dailyBoxService;
    }

    /**
     * List all daily boxes.
     *
     * @OA\Get(
     *     path="/api/v1/daily-boxes",
     *     summary="Get all daily boxes",
     *     tags={"DailyBoxes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all daily boxes retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DailyBoxResource")
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
        return DailyBoxResource::collection($this->dailyBoxService->getAll());
    }

    /**
     * Create a new daily box record.
     *
     * @OA\Post(
     *     path="/api/v1/daily-boxes",
     *     summary="Create a new daily box record",
     *     tags={"DailyBoxes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DailyBoxRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Daily box record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DailyBoxResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(DailyBoxRequest $request): DailyBoxResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new DailyBoxResource($this->dailyBoxService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a single daily box by ID.
     *
     * @OA\Get(
     *     path="/api/v1/daily-boxes/{id}",
     *     summary="Get a daily box by its ID",
     *     tags={"DailyBoxes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Daily box ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daily box details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DailyBoxResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Daily box not found"
     *     )
     * )
     */
    public function show(int $id): DailyBoxResource
    {
        return DailyBoxResource::make($this->dailyBoxService->getById($id));
    }

    /**
     * Update an existing daily box record.
     *
     * @OA\Put(
     *     path="/api/v1/daily-boxes/{id}",
     *     summary="Update an existing daily box record",
     *     tags={"DailyBoxes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Daily box ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DailyBoxRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daily box updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DailyBoxResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(DailyBoxRequest $request, int $id): DailyBoxResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new DailyBoxResource($this->dailyBoxService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a daily box record by its ID.
     *
     * @OA\Delete(
     *     path="/api/v1/daily-boxes/{id}",
     *     summary="Delete a daily box record by ID",
     *     tags={"DailyBoxes"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Daily box ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daily box record deleted successfully"
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
            $this->dailyBoxService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
