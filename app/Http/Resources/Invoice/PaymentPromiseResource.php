<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentPromiseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'promise_date' => $this->promise_date,
            'notes' => $this->notes,
            'status' => $this->status,
        ];
    }
}
