<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\CategoryRequest;
use App\Http\Resources\Inventory\CategoryResource;
use App\Services\App\Models\Inventory\CategoryService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for managing categories"
 * )
 *
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     type="object",
 *     title="Category Request Schema",
 *     description="Schema for creating or updating categories",
 *     required={"name", "url_key"},
 *     @OA\Property(property="name", type="string", description="Name of the category", example="Electronics"),
 *     @OA\Property(property="description", type="string", description="Description of the category", example="Products related to electronics and gadgets"),
 *     @OA\Property(property="url_key", type="string", format="string", description="Unique key for the category URL", example="electronics"),
 * )
 *
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     title="Category Resource Schema",
 *     description="Representation of a category",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the category", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the category", example="Electronics"),
 *     @OA\Property(property="description", type="string", description="Description of the category", example="Products related to electronics and gadgets"),
 *     @OA\Property(property="url_key", type="string", format="string", description="Unique key for the category URL", example="electronics"),
 * )
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * Constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Retrieve all categories.
     *
     * @OA\Get(
     *     path="/api/categories",
     *     summary="List all categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="List of categories retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
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
        return CategoryResource::collection($this->categoryService->getAll());
    }

    /**
     * Create a new category.
     *
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
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
    public function store(CategoryRequest $request)
    {
        try {
            return new CategoryResource($this->categoryService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific category by ID.
     *
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get a category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Category ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return CategoryResource::make($this->categoryService->getById($id));
    }

    /**
     * Update an existing category.
     *
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Category ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function update(CategoryRequest $request, int $id)
    {
        try {
            return new CategoryResource($this->categoryService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a category.
     *
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Category ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->categoryService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
