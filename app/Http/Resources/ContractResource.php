<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'customer_id' => $this->customer_id,
            'service_id' => $this->service_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_signed' => $this->is_signed,
            'signed_at' => $this->signed_at,
        ];
    }
}
