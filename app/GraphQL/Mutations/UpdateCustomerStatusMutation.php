<?php

namespace App\GraphQL\Mutations;

use App\Models\Customers\Customer;
use App\Models\User;
use App\Settings\GeneralProviderConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateCustomerStatusMutation
{
    public function resolve($_, array $args)
    {
        try {
            $customer = Customer::find($args['customer_id']);

            if (!$customer) {
                return [
                    'success' => false,
                    'message' => __('Customer not found.'),
                ];
            }

            // Si no hay usuario autenticado, establecer el usuario por defecto
            if (!Auth::check()) {
                $defaultUserId = GeneralProviderConfig::getDefaultUser();
                if ($defaultUserId) {
                    $defaultUser = User::find($defaultUserId);
                    if ($defaultUser) {
                        Auth::setUser($defaultUser);
                    }
                }
            }

            $customer->customer_status = $args['status'];
            $customer->save();

            return [
                'success' => true,
                'message' => __('Customer status updated successfully!'),
            ];

        } catch (\Exception $e) {
            Log::error('Error in UpdateCustomerStatusMutation', [
                'customer_id' => $args['customer_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error updating customer status: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
