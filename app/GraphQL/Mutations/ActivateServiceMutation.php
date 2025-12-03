<?php

namespace App\GraphQL\Mutations;

use App\Models\Services\Service;
use Illuminate\Support\Facades\Log;

class ActivateServiceMutation
{
    public function resolve($_, array $args)
    {
        try {
            $service = Service::find($args['service_id']);

            if (!$service) {
                return [
                    'success' => false,
                    'message' => __('Service not found.'),
                ];
            }

            $service->activate();

            return [
                'success' => true,
                'message' => __('Service activated successfully!'),
            ];

        } catch (\Exception $e) {
            Log::error('Error in ActivateServiceMutation', [
                'service_id' => $args['service_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error activating service: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
