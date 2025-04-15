<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplateRequest;
use App\Http\Resources\EmailTemplateResource;
use App\Services\EmailTemplateService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="EmailTemplates",
 *     description="API Endpoints for managing Email Templates"
 * )
 *
 * @OA\Schema(
 *     schema="EmailTemplateRequest",
 *     type="object",
 *     title="Email Template Request Schema",
 *     description="Schema for creating or updating an email template",
 *     required={"name", "subject", "body"},
 *     @OA\Property(property="id", type="integer", description="ID", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the email template", example="Welcome Email"),
 *     @OA\Property(property="subject", type="string", description="Subject of the email", example="Welcome to our service"),
 *     @OA\Property(property="body", type="string", description="Body content of the email", example="<p>Welcome to our service!</p>"),
 *     @OA\Property(property="styles", type="string", description="CSS styles for the email", example="<style>.main { color: blue; }</style>"),
 *     @OA\Property(property="entity", type="string", description="Entity related to the email template", example="User"),
 *     @OA\Property(property="is_active", type="integer", description="Status of the email template (1=active, 0=inactive)", example=1),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the template", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who last updated the template", example=1),
 *     @OA\Property(property="test_email", type="string", description="Email used for testing the template", example="test@example.com"),
 *     @OA\Property(property="description", type="string", description="Description of the email template", example="This template is for welcoming new users.")
 * )
 *
 * @OA\Schema(
 *     schema="EmailTemplateResource",
 *     type="object",
 *     title="Email Template Resource Schema",
 *     description="Representation of an Email Template",
 *     @OA\Property(property="id", type="integer", description="ID", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the email template", example="Welcome Email"),
 *     @OA\Property(property="subject", type="string", description="Subject of the email", example="Welcome to our service"),
 *     @OA\Property(property="body", type="string", description="Body content of the email", example="<p>Welcome to our service!</p>"),
 *     @OA\Property(property="styles", type="string", description="CSS styles for the email", example="<style>.main { color: blue; }</style>"),
 *     @OA\Property(property="entity", type="string", description="Entity related to the template", example="User"),
 *     @OA\Property(property="is_active", type="integer", description="Status of the email template", example=1),
 *     @OA\Property(property="created_by", type="integer", description="User ID who created the template", example=1),
 *     @OA\Property(property="updated_by", type="integer", description="User ID who last updated the template", example=1),
 *     @OA\Property(property="test_email", type="string", description="Email used for testing", example="test@example.com"),
 *     @OA\Property(property="description", type="string", description="Description of the email template", example="Template for welcoming users")
 * )
 */
class EmailTemplateController extends Controller
{
    /**
     * @var EmailTemplateService
     */
    protected EmailTemplateService $emailTemplateService;

    /**
     * DummyModel Constructor
     *
     * @param EmailTemplateService $emailTemplateService
     *
     */
    public function __construct(EmailTemplateService $emailTemplateService)
    {
        $this->emailTemplateService = $emailTemplateService;
    }

    /**
     * Get a list of all email templates.
     *
     * @OA\Get(
     *     path="/api/email-templates",
     *     summary="List all email templates",
     *     tags={"EmailTemplates"},
     *     @OA\Response(
     *         response=200,
     *         description="Email templates retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/EmailTemplateResource")
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
        return EmailTemplateResource::collection($this->emailTemplateService->getAll());
    }

    /**
     * Create a new email template.
     *
     * @OA\Post(
     *     path="/api/email-templates",
     *     summary="Create a new email template",
     *     tags={"EmailTemplates"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Email template created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplateResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(EmailTemplateRequest $request): EmailTemplateResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new EmailTemplateResource($this->emailTemplateService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve an email template by its ID.
     *
     * @OA\Get(
     *     path="/api/email-templates/{id}",
     *     summary="Retrieve an email template by ID",
     *     tags={"EmailTemplates"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Email Template ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email template retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplateResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email template not found"
     *     )
     * )
     */
    public function show(int $id): EmailTemplateResource
    {
        return EmailTemplateResource::make($this->emailTemplateService->getById($id));
    }

    /**
     * Update an email template by its ID.
     *
     * @OA\Put(
     *     path="/api/email-templates/{id}",
     *     summary="Update an email template",
     *     tags={"EmailTemplates"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Email Template ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email template updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplateResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(EmailTemplateRequest $request, int $id): EmailTemplateResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new EmailTemplateResource($this->emailTemplateService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an email template by its ID.
     *
     * @OA\Delete(
     *     path="/api/email-templates/{id}",
     *     summary="Delete an email template",
     *     tags={"EmailTemplates"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Email Template ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email template deleted successfully"
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
            $this->emailTemplateService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
