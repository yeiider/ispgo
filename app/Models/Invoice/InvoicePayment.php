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
        'payment_registered_by',
        'additional_information',
        'daily_box_id'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
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
            if ($payment->daily_box_id && $payment->payment_method === 'cash') {
                \App\Models\Finance\CashRegister::where('id', $payment->daily_box_id)->increment('current_balance', $payment->amount);
            }
        });

        // When a payment is updated, recalculate the invoice
        static::updated(function ($payment) {
            $payment->updateInvoiceTotals();
            
            // Si cambian el monto, el método o la caja, debemos ajustar el balance
            if ($payment->wasChanged(['amount', 'payment_method', 'daily_box_id'])) {
                $originalAmount = $payment->getOriginal('amount');
                $originalMethod = $payment->getOriginal('payment_method');
                $originalBoxId = $payment->getOriginal('daily_box_id');

                // Revertir el original si era efectivo
                if ($originalBoxId && $originalMethod === 'cash') {
                    \App\Models\Finance\CashRegister::where('id', $originalBoxId)->decrement('current_balance', $originalAmount);
                }

                // Aplicar el nuevo si es efectivo
                if ($payment->daily_box_id && $payment->payment_method === 'cash') {
                    \App\Models\Finance\CashRegister::where('id', $payment->daily_box_id)->increment('current_balance', $payment->amount);
                }
            }
        });

        // When a payment is deleted, recalculate the invoice
        static::deleted(function ($payment) {
            $payment->updateInvoiceTotals();
            if ($payment->daily_box_id && $payment->payment_method === 'cash') {
                \App\Models\Finance\CashRegister::where('id', $payment->daily_box_id)->decrement('current_balance', $payment->amount);
            }
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
