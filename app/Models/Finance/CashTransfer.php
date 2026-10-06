<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CashTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_type',
        'sender_id',
        'receiver_type',
        'receiver_id',
        'sender_cash_register_id',
        'receiver_cash_register_id',
        'amount',
        'received_amount',
        'status',
        'notes',
        'discrepancy_note',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    // Legacy relations
    public function senderCashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'sender_cash_register_id');
    }

    public function receiverCashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'receiver_cash_register_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? $model->created_by;
            $model->updated_by = Auth::id() ?? $model->updated_by;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? $model->updated_by;
        });
    }
}
