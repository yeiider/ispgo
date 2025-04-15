<?php

namespace App\Http\Resources\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id', $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'download_speed' => $this->download_speed,
            'upload_speed' => $this->upload_speed,
            'monthly_price' => $this->monthly_price,
            'overage_fee' => $this->overage_fee,
            'data_limit' => $this->data_limit,
            'unlimited_data' => $this->unlimited_data,
            'contract_period' => $this->contract_period,
            'promotions' => $this->promotions,
            'extras_included' => $this->extras_included,
            'geographic_availability' => $this->geographic_availability,
            'promotion_start_date' => $this->promotion_start_date,
            'promotion_end_date' => $this->promotion_end_date,
            'plan_image' => $this->plan_image,
            'customer_rating' => $this->customer_rating,
            'customer_reviews' => $this->customer_reviews,
            'service_compatibility' => $this->service_compatibility,
            'network_priority' => $this->network_priority,
            'technical_support' => $this->technical_support,
            'additional_benefits' => $this->additional_benefits,
            'connection_type' => $this->connection_type,
            'plan_type' => $this->plan_type,
            'modality_type' => $this->modality_type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
