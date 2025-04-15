<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Resources\PasswordResetResource;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="PasswordResets",
 *     description="API Endpoints for managing Password Resets"
 * )
 *
 * @OA\Schema(
 *     schema="PasswordResetRequest",
 *     type="object",
 *     title="Password Reset Request Schema",
 *     description="Schema for creating or updating a password reset entry",
 *     required={"email", "token"},
 *     @OA\Property(property="email", type="string", description="Email for the password reset", example="user@example.com"),
 *     @OA\Property(property="token", type="string", description="Token for the password reset", example="abc123xyz456")
 * )
 *
 * @OA\Schema(
 *     schema="PasswordResetResource",
 *     type="object",
 *     title="Password Reset Resource Schema",
 *     description="Representation of a Password Reset entry",
 *     @OA\Property(property="email", type="string", description="Email associated with the reset", example="user@example.com"),
 *     @OA\Property(property="token", type="string", description="Token used for the reset", example="abc123xyz456")
 * )
 */
class PasswordResetController extends Controller
{
    /**
     * @var PasswordResetService
     */
    protected PasswordResetService $passwordResetService;

    /**
     * DummyModel Constructor
     *
     * @param PasswordResetService $passwordResetService
     *
     */
    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * List all Password Resets.
     *
     * @OA\Get(
     *     path="/api/password-resets",
     *     summary="Retrieve all password resets",
     *     tags={"PasswordResets"},
     *     @OA\Response(
     *         response=200,
     *         description="List of password resets retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PasswordResetResource")
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
        return PasswordResetResource::collection($this->passwordResetService->getAll());
    }

    /**
     * Create a new Password Reset entry.
     *
     * @OA\Post(
     *     path="/api/password-resets",
     *     summary="Create a new password reset entry",
     *     tags={"PasswordResets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PasswordResetRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Password reset entry created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PasswordResetResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(PasswordResetRequest $request): PasswordResetResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new PasswordResetResource($this->passwordResetService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a Password Reset entry by ID.
     *
     * @OA\Get(
     *     path="/api/password-resets/{id}",
     *     summary="Get a password reset entry by ID",
     *     tags={"PasswordResets"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Password Reset entry ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset entry retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PasswordResetResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Password reset entry not found"
     *     )
     * )
     */
    public function show(int $id): PasswordResetResource
    {
        return PasswordResetResource::make($this->passwordResetService->getById($id));
    }

    /**
     * Update an existing Password Reset entry.
     *
     * @OA\Put(
     *     path="/api/password-resets/{id}",
     *     summary="Update an existing password reset entry",
     *     tags={"PasswordResets"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Password Reset entry ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PasswordResetRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset entry updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PasswordResetResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(PasswordResetRequest $request, int $id): PasswordResetResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new PasswordResetResource($this->passwordResetService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a Password Reset entry by ID.
     *
     * @OA\Delete(
     *     path="/api/password-resets/{id}",
     *     summary="Delete a password reset entry",
     *     tags={"PasswordResets"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Password Reset entry ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset entry deleted successfully"
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
            $this->passwordResetService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
