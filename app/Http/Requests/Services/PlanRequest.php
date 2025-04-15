<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'string',
            'download_speed' => 'integer',
            'upload_speed' => 'integer',
            'monthly_price' => 'numeric',
            'overage_fee' => 'numeric',
            'data_limit' => 'integer',
            'unlimited_data' => 'integer',
            'contract_period' => 'string|max:255',
            'promotions' => 'string',
            'extras_included' => 'string',
            'geographic_availability' => 'string',
            'promotion_start_date' => 'date',
            'promotion_end_date' => 'date',
            'plan_image' => 'string|max:255',
            'customer_rating' => 'numeric',
            'customer_reviews' => 'string',
            'service_compatibility' => 'string',
            'network_priority' => 'string|max:255',
            'technical_support' => 'string',
            'additional_benefits' => 'string',
            'connection_type' => 'string|max:255',
            'plan_type' => 'in:internet,television,telephonic,combo',
            'modality_type' => 'in:prepaid,postpaid',
            'status' => 'in:active,inactive',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }
}
