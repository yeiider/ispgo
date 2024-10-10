<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'address',
        'city',
        'state_province',
        'postal_code',
        'country',
        'address_type',
        'latitude',
        'longitude',
        'created_by',
        'updated_by'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getAddressNameAttribute()
    {
        return "{$this->address} - {$this->customer->full_name}";
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

    public function getAddressTypeAttribute($value): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('attribute.address_type.' . $value);
    }
}
