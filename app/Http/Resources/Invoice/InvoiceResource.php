<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'increment_id' => $this->increment_id,
            'service_id' => $this->service_id,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'user_id' => $this->user_id,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'amount' => $this->amount,
            'discount' => $this->discount,
            'outstanding_balance' => $this->outstanding_balance,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            'payment_support' => $this->payment_support,
            'daily_box_id' => $this->daily_box_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'additional_information' => $this->additional_information,
        ];
    }
}
