<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvoiceController
{

    public function index(): \Inertia\Response
    {
        return Inertia::render('Customer/Invoices/Index', [
            'invoices' => Invoice::with(['customer', 'service', 'user'])
                ->where('customer_id', $this->getCustomerId())
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
                ->through(fn($invoice) => [
                    'id' => $invoice->id,
                    'increment_id' => $invoice->increment_id,
                    'customer_id' => $invoice->customer_id,
                    'due_date' => $invoice->due_date,
                    'subtotal' => $invoice->subtotal,
                    'total' => $invoice->total,
                    'amount' => $invoice->amount,
                    'discount' => $invoice->discount,
                    'outstanding_balance' => $invoice->outstanding_balance,
                    'status' => $invoice->status,
                    'payment_method' => $invoice->payment_method,
                    'notes' => $invoice->notes,
                    'created_at' => $invoice->created_at,
                    'updated_at' => $invoice->updated_at,
                    'service' => $invoice->service,
                    'user' => $invoice->user,
                ])

        ]);
    }

    private function getCustomerId(): int|string|null
    {
        return Auth::guard('customer')->id();
    }
}
