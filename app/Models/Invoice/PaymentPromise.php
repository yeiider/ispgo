<?php

namespace App\Models\Invoice;

use App\Models\Customers\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPromise extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'customer_id', 'user_id', 'amount', 'promise_date', 'notes','status'];

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

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->user_id) && auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    
        static::addGlobalScope('orderByStatus', function (Builder $builder) {
            $builder->orderByRaw("FIELD(status, 'pending', 'fulfilled', 'cancelled')");
        });
    }
}
