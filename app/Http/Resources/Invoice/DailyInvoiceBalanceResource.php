<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyInvoiceBalanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'total_invoices' => $this->total_invoices,
            'paid_invoices' => $this->paid_invoices,
            'total_subtotal' => $this->total_subtotal,
            'total_tax' => $this->total_tax,
            'total_amount' => $this->total_amount,
            'total_discount' => $this->total_discount,
            'total_outstanding_balance' => $this->total_outstanding_balance,
            'total_revenue' => $this->total_revenue,
        ];
    }
}
