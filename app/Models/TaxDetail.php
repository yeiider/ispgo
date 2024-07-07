<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaxDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tax_identification_type',
        'tax_identification_number',
        'taxpayer_type',
        'fiscal_regime',
        'business_name',
        'enable_billing',
        'send_notifications',
        'send_invoice',
        'business_name',
        'created_by',
        'updated_by'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
