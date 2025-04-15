<?php

namespace App\Http\Resources\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'tax_identification_type' => $this->tax_identification_type,
            'tax_identification_number' => $this->tax_identification_number,
            'taxpayer_type' => $this->taxpayer_type,
            'fiscal_regime' => $this->fiscal_regime,
            'business_name' => $this->business_name,
            'enable_billing' => $this->enable_billing,
            'send_notifications' => $this->send_notifications,
            'send_invoice' => $this->send_invoice,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
