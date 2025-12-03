<?php

namespace App\GraphQL\Mutations;

use App\Models\Services\Service;
use Illuminate\Support\Facades\Log;

class SuspendServiceMutation
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

            $service->suspend();

            return [
                'success' => true,
                'message' => __('Services successfully suspended'),
            ];

        } catch (\Exception $e) {
            Log::error('Error in SuspendServiceMutation', [
                'service_id' => $args['service_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error suspending service: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
