<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerRequest;
use App\Http\Resources\Customers\CustomerResource;
use App\Services\Customers\CustomerService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Customers",
 *     description="API Endpoints for managing customers"
 * )
 *
 * @OA\Schema(
 *     schema="CustomerRequest",
 *     type="object",
 *     title="Customer Request Schema",
 *     description="Schema for creating or updating a customer",
 *     required={"first_name", "last_name", "email_address", "phone_number"},
 *     @OA\Property(property="first_name", type="string", description="Customer's first name", example="John"),
 *     @OA\Property(property="last_name", type="string", description="Customer's last name", example="Doe"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", description="Date of birth", example="1990-01-01"),
 *     @OA\Property(property="phone_number", type="string", description="Phone number", example="1234567890"),
 *     @OA\Property(property="email_address", type="string", description="Email address", example="john.doe@example.com"),
 *     @OA\Property(property="document_type", type="string", description="Document type", example="ID"),
 *     @OA\Property(property="identity_document", type="string", description="Identity document number", example="AB1234567"),
 *     @OA\Property(property="customer_status", type="string", enum={"active", "inactive"}, description="Status of the customer", example="active"),
 *     @OA\Property(property="additional_notes", type="string", description="Additional notes", example="Preferred contact via email."),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the customer", example=2),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who updated the customer", example=2),
 *     @OA\Property(property="password", type="string", description="Customer password", example="hashedPassword123"),
 *     @OA\Property(property="password_reset_token", type="string", description="Password reset token", example="resetToken123456"),
 *     @OA\Property(property="remember_token", type="string", description="Remember token", example="rememberMeToken123"),
 *     @OA\Property(property="password_reset_token_expiration", type="string", format="date", description="Expiration of the password reset token", example="2023-11-01"),
 * )
 *
 * @OA\Schema(
 *     schema="CustomerResource",
 *     type="object",
 *     title="Customer Resource Schema",
 *     description="Representation of a customer",
 *     @OA\Property(property="id", type="integer", description="Customer ID", example=1),
 *     @OA\Property(property="first_name", type="string", description="Customer first name", example="John"),
 *     @OA\Property(property="last_name", type="string", description="Customer last name", example="Doe"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", description="Date of birth", example="1990-01-01"),
 *     @OA\Property(property="phone_number", type="string", description="Phone number", example="1234567890"),
 *     @OA\Property(property="email_address", type="string", description="Email address", example="john.doe@example.com"),
 *     @OA\Property(property="document_type", type="string", description="Document type", example="ID"),
 *     @OA\Property(property="identity_document", type="string", description="Identity document number", example="AB1234567"),
 *     @OA\Property(property="customer_status", type="string", enum={"active", "inactive"}, description="Customer status", example="active"),
 *     @OA\Property(property="additional_notes", type="string", description="Additional notes", example="Preferred contact via email."),
 *     @OA\Property(property="created_by", type="integer", description="ID of the creator", example=2),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the updater", example=2),
 *     @OA\Property(property="password", type="string", description="Encrypted customer password", example="hashedPassword123"),
 *     @OA\Property(property="password_reset_token", type="string", description="Password reset token", example="resetToken123456"),
 *     @OA\Property(property="remember_token", type="string", description="Remember me token", example="rememberToken123"),
 *     @OA\Property(property="password_reset_token_expiration", type="string", format="date", description="Expiration of the reset token", example="2023-11-01"),
 * )
 */

class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    protected CustomerService $customerService;

    /**
     * DummyModel Constructor
     *
     * @param CustomerService $customerService
     *
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Get all customers.
     *
     * @OA\Get(
     *     path="/api/v1/customers",
     *     summary="List all customers",
     *     tags={"Customers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of customers successfully retrieved",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerResource")
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
        return CustomerResource::collection($this->customerService->getAll());
    }

    /**
     * Create a new customer.
     *
     * @OA\Post(
     *     path="/api/v1/customers",
     *     summary="Create a new customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
    public function store(CustomerRequest $request): CustomerResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new CustomerResource($this->customerService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a customer by ID.
     *
     * @OA\Get(
     *     path="/api/v1/customers/{id}",
     *     summary="Retrieve a customer by ID",
     *     tags={"Customers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer successfully retrieved",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function show(int $id): CustomerResource
    {
        return CustomerResource::make($this->customerService->getById($id));
    }

    /**
     * Update a customer.
     *
     * @OA\Put(
     *     path="/api/v1/customers/{id}",
     *     summary="Update a customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer successfully updated",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     )
     * )
     */
    public function update(CustomerRequest $request, int $id): CustomerResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new CustomerResource($this->customerService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a customer.
     *
     * @OA\Delete(
     *     path="/api/v1/customers/{id}",
     *     summary="Delete a customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->customerService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
