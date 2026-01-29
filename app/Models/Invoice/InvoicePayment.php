<?php

namespace App\Models\Invoice;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * InvoicePayment Model
 * 
 * Represents partial payments or "abonos" applied to an invoice.
 * These payments reduce the outstanding balance without changing the invoice's original total.
 * 
 * Use cases:
 * - Partial payments
 * - Installment payments
 * - Progressive abonos
 * 
 * For invoice adjustments (refunds, discounts), use CreditNote model instead.
 */
class InvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'payment_support',
        'additional_information'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'additional_information' => 'array',
        'amount' => 'float',
    ];

    /**
     * Get the invoice that this payment belongs to.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user who registered this payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // When a payment is created, update the invoice
        static::created(function ($payment) {
            $payment->updateInvoiceTotals();
        });

        // When a payment is updated, recalculate the invoice
        static::updated(function ($payment) {
            $payment->updateInvoiceTotals();
        });

        // When a payment is deleted, recalculate the invoice
        static::deleted(function ($payment) {
            $payment->updateInvoiceTotals();
        });
    }

    /**
     * Update the related invoice totals and status.
     */
    protected function updateInvoiceTotals(): void
    {
        if (!$this->invoice) {
            return;
        }

        $invoice = $this->invoice;
        
        // Recalculate amount (sum of all payments)
        $invoice->amount = $invoice->payments()->sum('amount');
        $invoice->outstanding_balance = $invoice->real_outstanding_balance;

        // Update status based on payment
        if ($invoice->isFullyPaid()) {
            $invoice->status = 'paid';
            $invoice->outstanding_balance = 0;
        } else if ($invoice->status === 'paid') {
            // If it was paid but now it's not (payment deleted), change to unpaid
            $invoice->status = 'unpaid';
        }

        $invoice->save();
    }
}
