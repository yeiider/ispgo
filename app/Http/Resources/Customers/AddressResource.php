<?php

namespace App\Http\Resources\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'address' => $this->address,
            'city' => $this->city,
            'state_province' => $this->state_province,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'address_type' => $this->address_type,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
