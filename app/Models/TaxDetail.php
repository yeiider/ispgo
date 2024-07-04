<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tax_identification_type',
        'tax_identification_number',
        'taxpayer_type',
        'fiscal_regime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
