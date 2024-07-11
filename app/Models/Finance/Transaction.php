<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'amount', 'date', 'type', 'payment_method', 'category', 'cash_register_id'
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}
