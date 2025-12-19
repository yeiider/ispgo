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
        'updated_by',
        'customer_name'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getAddressNameAttribute()
    {
        $full_name = $this->customer->full_name ? $this->customer->full_name : $this->getFullNameAttribute();
        return "{$this->address} - {$full_name}";
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
            if ($model->customer) {
                $model->customer_name = $model->customer->first_name . ' ' . $model->customer->last_name;
            }

        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
