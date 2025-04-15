<?php

namespace App\Http\Resources\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'router_id' => $this->router_id,
            'customer_id' => $this->customer_id,
            'plan_id' => $this->plan_id,
            'service_ip' => $this->service_ip,
            'username_router' => $this->username_router,
            'password_router' => $this->password_router,
            'service_status' => $this->service_status,
            'activation_date' => $this->activation_date,
            'deactivation_date' => $this->deactivation_date,
            'bandwidth' => $this->bandwidth,
            'mac_address' => $this->mac_address,
            'installation_date' => $this->installation_date,
            'service_notes' => $this->service_notes,
            'contract_id' => $this->contract_id,
            'support_contact' => $this->support_contact,
            'service_location' => $this->service_location,
            'service_type' => $this->service_type,
            'static_ip' => $this->static_ip,
            'data_limit' => $this->data_limit,
            'last_maintenance' => $this->last_maintenance,
            'billing_cycle' => $this->billing_cycle,
            'service_priority' => $this->service_priority,
            'assigned_technician' => $this->assigned_technician,
            'service_contract' => $this->service_contract,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
