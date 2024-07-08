<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyInvoiceBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_invoices',
        'paid_invoices',
        'total_subtotal',
        'total_tax',
        'total_amount',
        'total_discount',
        'total_outstanding_balance',
        'total_revenue',
    ];
    protected $casts = [
        'date' => 'date'
    ];
}
