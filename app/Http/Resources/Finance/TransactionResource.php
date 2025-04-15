<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'category' => $this->category,
            'cash_register_id' => $this->cash_register_id,
        ];
    }
}
