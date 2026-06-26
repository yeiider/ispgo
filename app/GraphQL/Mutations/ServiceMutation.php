<?php

namespace App\GraphQL\Mutations;

use App\Models\Services\Service;
use Illuminate\Support\Arr;

class ServiceMutation
{
    /**
     * Create a service and sync additional plans
     */
    public function create($_, array $args)
    {
        $additionalPlans = Arr::pull($args, 'additional_plans', []);
        
        // Remove relation from args before create to avoid "column not found"
        $service = Service::create($args);
        
        if (!empty($additionalPlans)) {
            $service->additionalPlans()->sync($additionalPlans);
        }
        
        return $service->fresh(['additionalPlans', 'customer', 'plan', 'router', 'address']);
    }

    /**
     * Update a service and sync additional plans
     */
    public function update($_, array $args)
    {
        $id = Arr::pull($args, 'id');
        $additionalPlans = Arr::get($args, 'additional_plans');
        
        $service = Service::findOrFail($id);
        
        // Filter out additional_plans from args to avoid "column not found"
        $updateData = Arr::except($args, ['additional_plans']);
        
        $service->update($updateData);
        
        if ($additionalPlans !== null) {
            $service->additionalPlans()->sync($additionalPlans);
        }
        
        return $service->fresh(['additionalPlans', 'customer', 'plan', 'router', 'address']);
    }
}
