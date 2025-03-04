<?php

namespace App\Models\Finance;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'amount', 'date', 'payment_method', 'category'
    ];

    protected $casts = [
        'date' => 'date'
    ];

}
