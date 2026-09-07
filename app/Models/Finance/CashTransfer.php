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
                } elseif ($newStatus === 'cancelled') {
                    if ($oldStatus === 'accepted') {
                        // Revert money: return to sender, remove from receiver
                        \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                            ->increment('current_balance', $model->amount);
                        \App\Models\Finance\CashRegister::where('id', $model->receiver_cash_register_id)
                            ->decrement('current_balance', $model->amount);
                    } elseif ($oldStatus === 'pending') {
                        // Return money to sender (was decremented on creation)
                        \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                            ->increment('current_balance', $model->amount);
                    }
                }
            }

            // Handle amount changes if not rejected or cancelled
            if ($model->isDirty('amount') && !$model->isDirty('status') && !in_array($model->status, ['rejected', 'cancelled'])) {
                $oldAmount = (float) $model->getOriginal('amount');
                $newAmount = (float) $model->amount;
                $diff = $newAmount - $oldAmount;

                if ($diff != 0) {
                    if ($model->status === 'pending') {
                        // Sender balance decreases by diff (if newAmount > oldAmount, diff > 0)
                        \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                            ->decrement('current_balance', $diff);
                    } elseif ($model->status === 'accepted') {
                        \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                            ->decrement('current_balance', $diff);
                        \App\Models\Finance\CashRegister::where('id', $model->receiver_cash_register_id)
                            ->increment('current_balance', $diff);
                    }
                }
            }
        });

        static::deleting(function ($model) {
            if ($model->status === 'accepted') {
                // Revert money: return to sender, remove from receiver
                \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                    ->increment('current_balance', $model->amount);
                \App\Models\Finance\CashRegister::where('id', $model->receiver_cash_register_id)
                    ->decrement('current_balance', $model->amount);
            } elseif ($model->status === 'pending') {
                // Return money to sender (was decremented on creation)
                \App\Models\Finance\CashRegister::where('id', $model->sender_cash_register_id)
                    ->increment('current_balance', $model->amount);
            }
            // If status is 'rejected', money was already returned when rejected. No action needed.
        });
    }
}
