<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\AddressRequest;
use App\Http\Resources\Customers\AddressResource;
use App\Services\Customers\AddressService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Addresses",
 *     description="API Endpoints for managing customer addresses"
 * )
 *
 * @OA\Schema(
 *     schema="AddressRequest",
 *     type="object",
 *     title="Address Request Schema",
 *     description="Schema for creating or updating an address",
 *     required={"customer_id", "customer_name", "address", "city", "state_province", "postal_code", "country", "address_type"},
 *     @OA\Property(property="customer_id", type="integer", description="Customer ID associated with the address", example=1),
 *     @OA\Property(property="customer_name", type="string", description="Customer name", example="John Doe"),
 *     @OA\Property(property="address", type="string", description="Street address", example="123 Main St"),
 *     @OA\Property(property="city", type="string", description="City", example="New York"),
 *     @OA\Property(property="state_province", type="string", description="State or Province", example="NY"),
 *     @OA\Property(property="postal_code", type="string", description="Postal or ZIP code", example="10001"),
 *     @OA\Property(property="country", type="string", description="Country", example="USA"),
 *     @OA\Property(property="address_type", type="string", enum={"billing", "shipping"}, description="Address type (billing or shipping)", example="billing"),
 *     @OA\Property(property="latitude", type="number", format="float", description="Latitude of the address", example=40.7128),
 *     @OA\Property(property="longitude", type="number", format="float", description="Longitude of the address", example=-74.0060),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the address", example=2),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who last updated the address", example=3),
 * )
 *
 * @OA\Schema(
 *     schema="AddressResource",
 *     type="object",
 *     title="Address Resource Schema",
 *     description="Representation of an address",
 *     @OA\Property(property="customer_id", type="integer", description="Customer ID", example=1),
 *     @OA\Property(property="customer_name", type="string", description="Customer name", example="John Doe"),
 *     @OA\Property(property="address", type="string", description="Street address", example="123 Main St"),
 *     @OA\Property(property="city", type="string", description="City", example="New York"),
 *     @OA\Property(property="state_province", type="string", description="State or Province", example="NY"),
 *     @OA\Property(property="postal_code", type="string", description="Postal or ZIP code", example="10001"),
 *     @OA\Property(property="country", type="string", description="Country", example="USA"),
 *     @OA\Property(property="address_type", type="string", enum={"billing", "shipping"}, description="Address type", example="billing"),
 *     @OA\Property(property="latitude", type="number", format="float", description="Latitude", example=40.7128),
 *     @OA\Property(property="longitude", type="number", format="float", description="Longitude", example=-74.0060),
 *     @OA\Property(property="created_by", type="integer", description="ID of the user who created the address", example=2),
 *     @OA\Property(property="updated_by", type="integer", description="ID of the user who last updated the address", example=3),
 * )
 */
class AddressController extends Controller
{
    /**
     * @var AddressService
     */
    protected AddressService $addressService;

    /**
     * DummyModel Constructor
     *
     * @param AddressService $addressService
     *
     */
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Get all addresses.
     *
     * @OA\Get(
     *     path="/api/addresses",
     *     summary="Get all addresses",
     *     tags={"Addresses"},
     *     @OA\Response(
     *         response=200,
     *         description="List of addresses retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AddressResource")
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
        return AddressResource::collection($this->addressService->getAll());
    }

    /**
     * Create a new address.
     *
     * @OA\Post(
     *     path="/api/addresses",
     *     summary="Create a new address",
     *     tags={"Addresses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AddressRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AddressResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(AddressRequest $request): AddressResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new AddressResource($this->addressService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get an address by ID.
     *
     * @OA\Get(
     *     path="/api/addresses/{id}",
     *     summary="Get an address by ID",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Address ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AddressResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found"
     *     )
     * )
     */
    public function show(int $id): AddressResource
    {
        return AddressResource::make($this->addressService->getById($id));
    }

    /**
     * Update an address.
     *
     * @OA\Put(
     *     path="/api/addresses/{id}",
     *     summary="Update an address",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Address ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AddressRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AddressResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(AddressRequest $request, int $id): AddressResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new AddressResource($this->addressService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an address.
     *
     * @OA\Delete(
     *     path="/api/addresses/{id}",
     *     summary="Delete an address",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Address ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address deleted successfully"
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
            $this->addressService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
