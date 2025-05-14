<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Model;

class InvoiceAdjustment extends Model
{
    protected $fillable = [
        'invoice_id', 'kind', 'amount', 'label',
        'metadata', 'created_by', 'source_type', 'source_id'

    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:2',
    ];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*--- Scopes útiles ---*/
    public function scopeCharges($q)
    {
        return $q->where('kind', 'charge');
    }

    public function scopeDiscounts($q)
    {
        return $q->where('kind', 'discount');
    }
}
