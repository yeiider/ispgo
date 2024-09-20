<?php

namespace Ispgo\Mikrotik\Services;

use App\Models\Services\Plan;
use App\Models\Services\Service;

class PlanFormatter
{
    /**
     * Formatear la información del plan y del servicio para su uso en la sincronización.
     *
     * @param Plan $plan
     * @param Service $service
     * @return array
     */
    public function formatPlanAndService(Plan $plan, Service $service): array
    {
        // Formatear los datos del plan y el servicio en un array homogéneo
        return [
            'service_ip' => $service->service_ip,
            'service_name' => $service->service_name,
            'plan_name' => $plan->name,
            'download_speed' => $plan->download_speed,
            'upload_speed' => $plan->upload_speed,
            'data_limit' => $plan->data_limit,
            'unlimited_data' => $plan->unlimited_data,
            'overage_fee' => $plan->overage_fee,
            'connection_type' => $plan->connection_type,
            'modality_type' => $plan->modality_type,
            'plan_type' => $plan->plan_type,
            'status' => $plan->status,
            'network_priority' => $plan->network_priority,
            'contract_period' => $plan->contract_period,
            'geographic_availability' => $plan->geographic_availability,
            'technical_support' => $plan->technical_support,
            'created_by' => $plan->created_by,
            'updated_by' => $plan->updated_by,
            'promotion_start_date' => $plan->promotion_start_date,
            'promotion_end_date' => $plan->promotion_end_date
        ];
    }

    /**
     * Formatear la información del plan y del servicio para su uso en la sincronización.
     *
     * @param Plan $plan
     * @param Service $service
     * @return array
     */
    public function formatPlan(Plan $plan): array
    {
        // Formatear los datos del plan y el servicio en un array homogéneo
        return [
            'plan_name' => $plan->name,
            'download_speed' => $plan->download_speed,
            'upload_speed' => $plan->upload_speed,
            'data_limit' => $plan->data_limit,
            'unlimited_data' => $plan->unlimited_data,
            'overage_fee' => $plan->overage_fee,
            'connection_type' => $plan->connection_type,
            'modality_type' => $plan->modality_type,
            'plan_type' => $plan->plan_type,
            'status' => $plan->status,
            'network_priority' => $plan->network_priority,
            'contract_period' => $plan->contract_period,
            'geographic_availability' => $plan->geographic_availability,
            'technical_support' => $plan->technical_support,
            'created_by' => $plan->created_by,
            'updated_by' => $plan->updated_by,
            'promotion_start_date' => $plan->promotion_start_date,
            'promotion_end_date' => $plan->promotion_end_date
        ];
    }
}
