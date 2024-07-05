<?php

namespace App\Models;

use App\Events\CustomerCreated;
use App\Events\CustomerStatusUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    use HasFactory;

    protected $casts = [
        'date_of_birth' => 'date',
    ];
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'phone_number',
        'email_address',
        'document_type',
        'identity_document',
        'customer_status',
        'additional_notes',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function taxDetails()
    {
        return $this->hasOne(TaxDetail::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    protected static function booted()
    {
        /* static::addGlobalScope('active', function (Builder $builder) {
             $builder->where('customer_status', 'active');
             //esto hace que cada que hagan una llamada a la base de datos solo trae clientes activos
         })*/;

        static::creating(function ($customer) {
            // Código a ejecutar antes de crear un cliente
        });

        static::created(function ($customer) {
            event(new CustomerCreated($customer));
        });
        static::updating(function ($customer) {
            if ($customer->isDirty('customer_status')) {
                event(new CustomerStatusUpdated($customer));
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('customer_status', 'active');
        //how use $activeCustomers = Customer::active()->get();
    }
}
