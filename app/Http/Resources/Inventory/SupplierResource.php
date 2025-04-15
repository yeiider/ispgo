<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contact' => $this->contact,
            'document' => $this->document,
            'description' => $this->description,
            'country' => $this->country,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
