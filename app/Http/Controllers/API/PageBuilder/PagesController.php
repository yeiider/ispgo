<?php

namespace App\Http\Controllers\API\PageBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageBuilder\PagesRequest;
use App\Http\Resources\PageBuilder\PagesResource;
use App\Services\App\Models\PageBuilder\PagesService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Pages",
 *     description="API Endpoints for managing pages"
 * )
 *
 * @OA\Schema(
 *     schema="PagesRequest",
 *     type="object",
 *     title="Pages Request Schema",
 *     description="Schema for creating or updating pages",
 *     required={"name", "layout", "data"},
 *     @OA\Property(property="name", type="string", maxLength=256, description="Name of the page", example="Home Page"),
 *     @OA\Property(property="layout", type="string", maxLength=256, description="Layout of the page", example="default"),
 *     @OA\Property(property="data", type="string", description="Page content data in JSON format")
 * )
 *
 * @OA\Schema(
 *     schema="PagesResource",
 *     type="object",
 *     title="Pages Resource Schema",
 *     description="Representation of a page",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/PagesRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the page", example=1)
 *         )
 *     }
 * )
 */
class PagesController extends Controller
{
    /**
     * @var PagesService
     */
    protected PagesService $pagesService;

    /**
     * Constructor.
     *
     * @param PagesService $pagesService
     */
    public function __construct(PagesService $pagesService)
    {
        $this->pagesService = $pagesService;
    }

    /**
     * Retrieve all pages.
     *
     * @OA\Get(
     *     path="/api/pages",
     *     summary="List all pages",
     *     tags={"Pages"},
     *     @OA\Response(
     *         response=200,
     *         description="List of pages retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PagesResource")
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
        return PagesResource::collection($this->pagesService->getAll());
    }

    /**
     * Create a new page.
     *
     * @OA\Post(
     *     path="/api/pages",
     *     summary="Create a new page",
     *     tags={"Pages"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PagesRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Page created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PagesResource")
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
    public function store(PagesRequest $request)
    {
        try {
            return new PagesResource($this->pagesService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific page by ID.
     *
     * @OA\Get(
     *     path="/api/pages/{id}",
     *     summary="Get a page by ID",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Page ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PagesResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return PagesResource::make($this->pagesService->getById($id));
    }

    /**
     * Update an existing page.
     *
     * @OA\Put(
     *     path="/api/pages/{id}",
     *     summary="Update a page",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Page ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PagesRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PagesResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     )
     * )
     */
    public function update(PagesRequest $request, int $id)
    {
        try {
            return new PagesResource($this->pagesService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a page.
     *
     * @OA\Delete(
     *     path="/api/pages/{id}",
     *     summary="Delete a page",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Page ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->pagesService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
