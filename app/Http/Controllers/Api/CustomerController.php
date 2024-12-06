<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        DB::beginTransaction();

        try {
            // Create customer
            $customer = Customer::create($request->only(array_keys($this->getCustomerRules())));

            // Create addresses
            $this->createAddresses($customer, $request->input('addresses'));

            // Create services
            $this->createServices($customer, $request->input('services'));

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $customer->load('addresses', 'services'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo crear el cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get validation rules for the request.
     */
    private function getValidationRules(): array
    {
        return array_merge(
            $this->getCustomerRules(),
            $this->getAddressRules(),
            $this->getServiceRules()
        );
    }

    /**
     * Get validation rules for customer data.
     */
    private function getCustomerRules(): array
    {
        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'required|max:12',
            'email_address' => 'required|email|max:100|unique:customers,email_address',
            'document_type' => 'required|max:20',
            'identity_document' => 'required|max:100',
            'customer_status' => 'required|in:active,inactive',
            'additional_notes' => 'nullable|string',
        ];
    }

    /**
     * Get validation rules for addresses.
     */
    private function getAddressRules(): array
    {
        return [
            'addresses' => 'required|array|min:1',
            'addresses.*.address' => 'required|max:100',
            'addresses.*.city' => 'required|max:100',
            'addresses.*.state_province' => 'required|max:100',
            'addresses.*.postal_code' => 'required|max:20',
            'addresses.*.country' => 'required|max:100',
            'addresses.*.address_type' => 'required|in:billing,shipping',
            'addresses.*.latitude' => 'nullable|numeric',
            'addresses.*.longitude' => 'nullable|numeric',
        ];
    }

    /**
     * Get validation rules for services.
     */
    private function getServiceRules(): array
    {
        return [
            'services' => 'required|array|min:1',
            'services.*.router_id' => 'required|exists:routers,id',
            'services.*.service_ip' => 'required|ip',
            'services.*.username_router' => 'required|string|max:255',
            'services.*.password_router' => 'required|string|max:255',
            'services.*.service_status' => 'required|in:active,inactive,suspended,pending,free',
            'services.*.activation_date' => 'required|date',
            'services.*.plan_id' => 'required|exists:plans,id',
        ];
    }

    /**
     * Handle addresses creation.
     */
    private function createAddresses(Customer $customer, array $addresses)
    {
        foreach ($addresses as $address) {
            $customer->addresses()->create($address);
        }
    }

    /**
     * Handle services creation.
     */
    private function createServices(Customer $customer, array $services)
    {
        foreach ($services as $service) {
            $customer->services()->create($service + ['customer_id' => $customer->id, "service_location" => $customer->addresses()->first()->id]);
        }
    }

    /**
     * Return validation error response.
     */
    private function validationErrorResponse($validator)
    {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);
    }
}
