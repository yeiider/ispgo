<?php

namespace App\Http\Controllers\API\PageBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageBuilder\PageTranslationRequest;
use App\Http\Resources\PageBuilder\PageTranslationResource;
use App\Services\PageBuilder\PageTranslationService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="PageTranslations",
 *     description="API Endpoints for managing page translations"
 * )
 *
 * @OA\Schema(
 *     schema="PageTranslationRequest",
 *     type="object",
 *     title="Page Translation Request Schema",
 *     description="Schema for creating or updating page translations",
 *     required={"page_id", "locale", "title"},
 *     @OA\Property(property="page_id", type="integer", description="ID of the page to which the translation belongs", example=1),
 *     @OA\Property(property="locale", type="string", maxLength=50, description="Language or locale of the translation", example="en"),
 *     @OA\Property(property="title", type="string", maxLength=255, description="Title of the translated page", example="Homepage"),
 *     @OA\Property(property="meta_title", type="string", maxLength=255, description="SEO meta title of the page", example="Welcome to our homepage"),
 *     @OA\Property(property="meta_description", type="string", maxLength=255, description="SEO meta description", example="This is the homepage of our site"),
 *     @OA\Property(property="route", type="string", maxLength=255, description="Route or URL for the translated page", example="/en/home")
 * )
 *
 * @OA\Schema(
 *     schema="PageTranslationResource",
 *     type="object",
 *     title="Page Translation Resource Schema",
 *     description="Representation of a single page translation",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/PageTranslationRequest"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", description="Unique identifier of the page translation", example=1)
 *         )
 *     }
 * )
 */
class PageTranslationController extends Controller
{
    /**
     * @var PageTranslationService
     */
    protected PageTranslationService $pageTranslationService;

    /**
     * Constructor.
     *
     * @param PageTranslationService $pageTranslationService
     */
    public function __construct(PageTranslationService $pageTranslationService)
    {
        $this->pageTranslationService = $pageTranslationService;
    }

    /**
     * Retrieve all page translations.
     *
     * @OA\Get(
     *     path="/api/page-translations",
     *     summary="List all page translations",
     *     tags={"PageTranslations"},
     *     @OA\Response(
     *         response=200,
     *         description="List of page translations retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PageTranslationResource")
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
        return PageTranslationResource::collection($this->pageTranslationService->getAll());
    }

    /**
     * Create a new page translation.
     *
     * @OA\Post(
     *     path="/api/page-translations",
     *     summary="Create a new page translation",
     *     tags={"PageTranslations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PageTranslationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Page translation created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PageTranslationResource")
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
    public function store(PageTranslationRequest $request)
    {
        try {
            return new PageTranslationResource($this->pageTranslationService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific page translation by ID.
     *
     * @OA\Get(
     *     path="/api/page-translations/{id}",
     *     summary="Get a page translation by ID",
     *     tags={"PageTranslations"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Page translation ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page translation retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PageTranslationResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page translation not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return PageTranslationResource::make($this->pageTranslationService->getById($id));
    }

    /**
     * Update an existing page translation.
     *
     * @OA\Put(
     *     path="/api/page-translations/{id}",
     *     summary="Update a page translation",
     *     tags={"PageTranslations"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Page translation ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PageTranslationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page translation updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PageTranslationResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page translation not found"
     *     )
     * )
     */
    public function update(PageTranslationRequest $request, int $id)
    {
        try {
            return new PageTranslationResource($this->pageTranslationService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a page translation.
     *
     * @OA\Delete(
     *     path="/api/page-translations/{id}",
     *     summary="Delete a page translation",
     *     tags={"PageTranslations"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Page translation ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page translation deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page translation not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->pageTranslationService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
