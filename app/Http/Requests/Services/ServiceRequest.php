<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'router_id' => 'integer',
            'customer_id' => 'integer',
            'plan_id' => 'integer',
            'service_ip' => 'string|max:255',
            'username_router' => 'string|max:255',
            'password_router' => 'string|max:255',
            'service_status' => 'in:active,inactive,suspended,pending,free',
            'activation_date' => 'date',
            'deactivation_date' => 'date',
            'bandwidth' => 'integer',
            'mac_address' => 'string|max:255',
            'installation_date' => 'date',
            'service_notes' => 'string',
            'contract_id' => 'integer',
            'support_contact' => 'string|max:255',
            'service_location' => 'string|max:255',
            'service_type' => 'in:ftth,adsl,satellite',
            'static_ip' => 'integer',
            'data_limit' => 'integer',
            'last_maintenance' => 'date',
            'billing_cycle' => 'string|max:255',
            'service_priority' => 'in:normal,high,critical',
            'assigned_technician' => 'integer',
            'service_contract' => 'string',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }
}
