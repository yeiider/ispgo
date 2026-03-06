<?php

namespace App\Models\Invoice;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CreditNote Model
 * 
 * Represents actual credit notes for invoice adjustments:
 * - Product returns/refunds
 * - Post-sale discounts
 * - Invoice corrections
 * - Reduces the invoice total value
 * 
 * For partial payments or "abonos", use InvoicePayment model instead.
 */
class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'user_id', 'amount', 'issue_date', 'reason'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
