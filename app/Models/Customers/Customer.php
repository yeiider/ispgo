<?php

namespace App\Models\Customers;

use App\Events\CustomerCreated;
use App\Events\CustomerStatusUpdated;
use App\Models\Invoice\Invoice;
use App\Models\Services\Service;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements MustVerifyEmail
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
        'created_by',
        'updated_by',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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
        return ucwords("{$this->first_name} {$this->last_name}");
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

            $customer->created_by = Auth::id();
            $customer->updated_by = Auth::id();
            event(new CustomerCreated($customer));
        });
        static::updating(function ($customer) {
            if ($customer->isDirty('customer_status')) {
                $customer->updated_by = Auth::id();
                event(new CustomerStatusUpdated($customer));
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('customer_status', 'active');
        //how use $activeCustomers = Customer::active()->get();
    }

    public static function findByIdentityDocument($identityDocument)
    {
        return self::where('identity_document', $identityDocument)->first();
    }

    public function getLastInvoice()
    {
        return $this->invoices()->orderBy('created_at', 'desc')->first();
    }


}
