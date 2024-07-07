<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPromise extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'customer_id', 'user_id', 'amount', 'promise_date', 'notes'];

    protected $casts = [
        'promise_date' => 'date'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
