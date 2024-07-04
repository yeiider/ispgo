<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
