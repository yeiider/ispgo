<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\DocumentTypeRequest;
use App\Http\Resources\Customers\DocumentTypeResource;
use App\Services\App\Models\Customers\DocumentTypeService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Document Types",
 *     description="API Endpoints for managing document types"
 * )
 *
 * @OA\Schema(
 *     schema="DocumentTypeRequest",
 *     type="object",
 *     title="Document Type Request Schema",
 *     description="Schema for creating or updating a document type",
 *     required={"code", "name"},
 *     @OA\Property(property="code", type="string", description="Unique code for the document type", example="ID"),
 *     @OA\Property(property="name", type="string", description="Name of the document type", example="Identity Document"),
 * )
 *
 * @OA\Schema(
 *     schema="DocumentTypeResource",
 *     type="object",
 *     title="Document Type Resource Schema",
 *     description="Representation of a document type",
 *     @OA\Property(property="id", type="integer", description="Document type ID", example=1),
 *     @OA\Property(property="code", type="string", description="Unique code for the document type", example="ID"),
 *     @OA\Property(property="name", type="string", description="Name of the document type", example="Identity Document"),
 * )
 */
class DocumentTypeController extends Controller
{
    /**
     * @var DocumentTypeService
     */
    protected DocumentTypeService $documentTypeService;

    /**
     * DummyModel Constructor
     *
     * @param DocumentTypeService $documentTypeService
     *
     */
    public function __construct(DocumentTypeService $documentTypeService)
    {
        $this->documentTypeService = $documentTypeService;
    }

    /**
     * Retrieve all document types.
     *
     * @OA\Get(
     *     path="/api/document-types",
     *     summary="List all document types",
     *     tags={"Document Types"},
     *     @OA\Response(
     *         response=200,
     *         description="List of document types retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DocumentTypeResource")
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
        return DocumentTypeResource::collection($this->documentTypeService->getAll());
    }

    /**
     * Create a new document type.
     *
     * @OA\Post(
     *     path="/api/document-types",
     *     summary="Create a new document type",
     *     tags={"Document Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DocumentTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Document type created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentTypeResource")
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
    public function store(DocumentTypeRequest $request): DocumentTypeResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new DocumentTypeResource($this->documentTypeService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a document type by ID.
     *
     * @OA\Get(
     *     path="/api/document-types/{id}",
     *     summary="Get a document type by ID",
     *     tags={"Document Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Document type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document type retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentTypeResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document type not found"
     *     )
     * )
     */
    public function show(int $id): DocumentTypeResource
    {
        return DocumentTypeResource::make($this->documentTypeService->getById($id));
    }

    /**
     * Update an existing document type.
     *
     * @OA\Put(
     *     path="/api/document-types/{id}",
     *     summary="Update a document type",
     *     tags={"Document Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Document type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DocumentTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document type updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentTypeResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document type not found"
     *     )
     * )
     */
    public function update(DocumentTypeRequest $request, int $id): DocumentTypeResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new DocumentTypeResource($this->documentTypeService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a document type.
     *
     * @OA\Delete(
     *     path="/api/document-types/{id}",
     *     summary="Delete a document type",
     *     tags={"Document Types"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Document type ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document type deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document type not found"
     *     )
     * )
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->documentTypeService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
