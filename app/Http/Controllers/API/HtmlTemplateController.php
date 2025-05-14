<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HtmlTemplateRequest;
use App\Http\Resources\HtmlTemplateResource;
use App\Services\HtmlTemplateService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="HtmlTemplates",
 *     description="API Endpoints for managing HTML Templates"
 * )
 *
 * @OA\Schema(
 *     schema="HtmlTemplateRequest",
 *     type="object",
 *     title="Html Template Request Schema",
 *     description="Schema for creating or updating an HTML template",
 *     required={"name", "body"},
 *     @OA\Property(property="name", type="string", description="Name of the HTML template", example="Default Template"),
 *     @OA\Property(property="body", type="string", description="HTML body content of the template", example="<div>Template Body</div>"),
 *     @OA\Property(property="styles", type="string", description="CSS styles for the template", example="<style>.main { color: black; }</style>"),
 *     @OA\Property(property="entity", type="string", description="Entity associated with this HTML template", example="Product"),
 * )
 *
 * @OA\Schema(
 *     schema="HtmlTemplateResource",
 *     type="object",
 *     title="HTML Template Resource Schema",
 *     description="Representation of an HTML Template",
 *     @OA\Property(property="id", type="integer", description="Unique ID of the template", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the HTML template", example="Default Template"),
 *     @OA\Property(property="body", type="string", description="HTML body content of the template", example="<div>Template Body</div>"),
 *     @OA\Property(property="styles", type="string", description="CSS styles for the template", example="<style>.main { color: black; }</style>"),
 *     @OA\Property(property="entity", type="string", description="Entity associated with this HTML template", example="Product"),
 * )
 */
class HtmlTemplateController extends Controller
{
    /**
     * @var HtmlTemplateService
     */
    protected HtmlTemplateService $htmlTemplateService;

    /**
     * DummyModel Constructor
     *
     * @param HtmlTemplateService $htmlTemplateService
     *
     */
    public function __construct(HtmlTemplateService $htmlTemplateService)
    {
        $this->htmlTemplateService = $htmlTemplateService;
    }

    /**
     * Retrieve all HTML Templates.
     *
     * @OA\Get(
     *     path="/api/v1/html-templates",
     *     summary="Get all HTML templates",
     *     tags={"HtmlTemplates"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of HTML templates retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/HtmlTemplateResource")
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
        return HtmlTemplateResource::collection($this->htmlTemplateService->getAll());
    }

    /**
     * Create a new HTML Template.
     *
     * @OA\Post(
     *     path="/api/v1/html-templates",
     *     summary="Create a new HTML template",
     *     tags={"HtmlTemplates"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/HtmlTemplateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="HTML template created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/HtmlTemplateResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(HtmlTemplateRequest $request): HtmlTemplateResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new HtmlTemplateResource($this->htmlTemplateService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve an HTML Template by ID.
     *
     * @OA\Get(
     *     path="/api/v1/html-templates/{id}",
     *     summary="Get an HTML template by ID",
     *     tags={"HtmlTemplates"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="HTML Template ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="HTML template retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/HtmlTemplateResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTML template not found"
     *     )
     * )
     */
    public function show(int $id): HtmlTemplateResource
    {
        return HtmlTemplateResource::make($this->htmlTemplateService->getById($id));
    }

    /**
     * Update an HTML Template.
     *
     * @OA\Put(
     *     path="/api/v1/html-templates/{id}",
     *     summary="Update an HTML template",
     *     tags={"HtmlTemplates"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="HTML Template ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/HtmlTemplateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="HTML template updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/HtmlTemplateResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(HtmlTemplateRequest $request, int $id): HtmlTemplateResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new HtmlTemplateResource($this->htmlTemplateService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an HTML Template by ID.
     *
     * @OA\Delete(
     *     path="/api/html-templates/{id}",
     *     summary="Delete an HTML template",
     *     tags={"HtmlTemplates"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="HTML Template ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="HTML template deleted successfully"
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
            $this->htmlTemplateService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
