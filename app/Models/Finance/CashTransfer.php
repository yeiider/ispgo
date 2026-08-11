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
        'sender_cash_register_id',
        'receiver_cash_register_id',
        'amount',
        'status',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

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

        static::created(function ($model) {
            if ($model->status === 'pending') {
                \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                    ->decrement('current_balance', $model->amount);
            }
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? $model->updated_by;

            // Handle status changes
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                if ($oldStatus === 'pending' && $newStatus === 'accepted') {
                    // It was delivered to the admin, add to their cash register
                    \App\Models\Finance\CashRegister::where('id', $model->receiver_cash_register_id)
                        ->increment('current_balance', $model->amount);
                } elseif ($oldStatus === 'pending' && $newStatus === 'rejected') {
                    // It was rejected, return to the sender's cash register
                    \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                        ->increment('current_balance', $model->amount);
                }
            }
        });
    }
}
