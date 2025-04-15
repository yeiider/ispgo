<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'issue_date' => $this->issue_date,
            'reason' => $this->reason,
        ];
    }
}
