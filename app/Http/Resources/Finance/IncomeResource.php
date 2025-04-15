<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'payment_method' => $this->payment_method,
            'category' => $this->category,
            'customer_id' => $this->customer_id,
            'invoice_id' => $this->invoice_id,
        ];
    }
}
