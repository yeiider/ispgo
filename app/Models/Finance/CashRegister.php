<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = ['initial_balance', 'current_balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
