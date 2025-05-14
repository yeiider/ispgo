<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ProductRequest;
use App\Http\Resources\Inventory\ProductResource;
use App\Services\Inventory\ProductService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for managing products"
 * )
 *
 * @OA\Schema(
 *     schema="ProductRequest",
 *     type="object",
 *     title="Product Request Schema",
 *     description="Schema for creating or updating products",
 *     required={"name", "sku", "price", "url_key", "warehouse_id", "category_id"},
 *     @OA\Property(property="name", type="string", description="Name of the product", example="Laptop"),
 *     @OA\Property(property="sku", type="string", description="SKU (Stock Keeping Unit) of the product", example="LAP12345"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the product", example=1200.50),
 *     @OA\Property(property="special_price", type="number", format="float", description="Special discount price for the product", example=1000.00),
 *     @OA\Property(property="cost_price", type="number", format="float", description="Cost price of the product", example=800.00),
 *     @OA\Property(property="brand", type="string", description="Brand of the product", example="Dell"),
 *     @OA\Property(property="qty", type="string", description="Quantity of the product available", example="50"),
 *     @OA\Property(property="image", type="string", description="URL of the product image", example="/images/laptop.png"),
 *     @OA\Property(property="description", type="string", description="Description of the product", example="High-performance laptop for work and gaming"),
 *     @OA\Property(property="reference", type="string", description="Reference or additional product information", example="Model X"),
 *     @OA\Property(property="taxes", type="number", format="float", description="Tax percentage applicable to the product", example=18.00),
 *     @OA\Property(property="status", type="integer", description="Status of the product (1 for active, 0 for inactive)", example=1),
 *     @OA\Property(property="url_key", type="string", description="Unique key for the product URL", example="laptop-dell-x"),
 *     @OA\Property(property="warehouse_id", type="integer", description="Warehouse ID where the product is stored", example=1),
 *     @OA\Property(property="category_id", type="integer", description="Category ID to which the product belongs", example=3),
 * )
 *
 * @OA\Schema(
 *     schema="ProductResource",
 *     type="object",
 *     title="Product Resource Schema",
 *     description="Representation of a product",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the product", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the product", example="Laptop"),
 *     @OA\Property(property="sku", type="string", description="SKU (Stock Keeping Unit) of the product", example="LAP12345"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the product", example=1200.50),
 *     @OA\Property(property="special_price", type="number", format="float", description="Special discount price for the product", example=1000.00),
 *     @OA\Property(property="cost_price", type="number", format="float", description="Cost price of the product", example=800.00),
 *     @OA\Property(property="brand", type="string", description="Brand of the product", example="Dell"),
 *     @OA\Property(property="qty", type="string", description="Quantity of the product available", example="50"),
 *     @OA\Property(property="image", type="string", description="URL of the product image", example="/images/laptop.png"),
 *     @OA\Property(property="description", type="string", description="Description of the product", example="High-performance laptop for work and gaming"),
 *     @OA\Property(property="reference", type="string", description="Reference or additional product information", example="Model X"),
 *     @OA\Property(property="taxes", type="number", format="float", description="Tax percentage applicable to the product", example=18.00),
 *     @OA\Property(property="status", type="integer", description="Status of the product (1 for active, 0 for inactive)", example=1),
 *     @OA\Property(property="url_key", type="string", description="Unique key for the product URL", example="laptop-dell-x"),
 *     @OA\Property(property="warehouse_id", type="integer", description="Warehouse ID where the product is stored", example=1),
 *     @OA\Property(property="category_id", type="integer", description="Category ID to which the product belongs", example=3),
 * )
 */
class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * Constructor.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Retrieve all products.
     *
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="List all products",
     *     tags={"Products"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of products retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductResource")
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
        return ProductResource::collection($this->productService->getAll());
    }

    /**
     * Create a new product.
     *
     * @OA\Post(
     *     path="/api/v1/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
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
    public function store(ProductRequest $request)
    {
        try {
            return new ProductResource($this->productService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a specific product by ID.
     *
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     summary="Get a product by ID",
     *     tags={"Products"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Product ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        return ProductResource::make($this->productService->getById($id));
    }

    /**
     * Update an existing product.
     *
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     summary="Update a product",
     *     tags={"Products"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Product ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function update(ProductRequest $request, int $id)
    {
        try {
            return new ProductResource($this->productService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a product.
     *
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     summary="Delete a product",
     *     tags={"Products"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Product ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->productService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
