<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CashTransfer extends Model
{
    use HasFactory;

    public const STATUS_PENDING   = 'pending';
    public const STATUS_ACCEPTED  = 'accepted';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

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
        'received_amount' => 'decimal:2',
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

    /* -----------------------------------------------------------------
     |  Scopes
     | -----------------------------------------------------------------
     | Cubren tanto el esquema polimórfico como el legacy, para que los
     | reportes no dependan de cuál de las dos columnas esté llena.
     */

    public function scopeSentFromCashRegister(Builder $query, int|string $cashRegisterId): Builder
    {
        return $query->where(function (Builder $q) use ($cashRegisterId) {
            $q->where('sender_cash_register_id', $cashRegisterId)
              ->orWhere(function (Builder $q2) use ($cashRegisterId) {
                  $q2->where('sender_type', CashRegister::class)
                     ->where('sender_id', $cashRegisterId);
              });
        });
    }

    public function scopeReceivedByCashRegister(Builder $query, int|string $cashRegisterId): Builder
    {
        return $query->where(function (Builder $q) use ($cashRegisterId) {
            $q->where('receiver_cash_register_id', $cashRegisterId)
              ->orWhere(function (Builder $q2) use ($cashRegisterId) {
                  $q2->where('receiver_type', CashRegister::class)
                     ->where('receiver_id', $cashRegisterId);
              });
        });
    }

    public function scopeInvolvingVault(Builder $query, int|string $vaultId): Builder
    {
        return $query->where(function (Builder $q) use ($vaultId) {
            $q->where(function (Builder $s) use ($vaultId) {
                $s->where('sender_type', Vault::class)->where('sender_id', $vaultId);
            })->orWhere(function (Builder $r) use ($vaultId) {
                $r->where('receiver_type', Vault::class)->where('receiver_id', $vaultId);
            });
        });
    }

    /* -----------------------------------------------------------------
     |  Accessors (expuestos en GraphQL)
     | -----------------------------------------------------------------
     */

    /**
     * Monto que realmente impacta los saldos: el recibido si ya fue aceptada,
     * el enviado mientras está pendiente.
     */
    public function getEffectiveAmountAttribute(): float
    {
        if ($this->status === self::STATUS_ACCEPTED && $this->received_amount !== null) {
            return (float) $this->received_amount;
        }

        return (float) $this->amount;
    }

    public function getSenderNameAttribute(): ?string
    {
        return $this->resolvePartyName($this->sender, $this->senderCashRegister);
    }

    public function getReceiverNameAttribute(): ?string
    {
        return $this->resolvePartyName($this->receiver, $this->receiverCashRegister);
    }

    public function getSenderUserNameAttribute(): ?string
    {
        return $this->resolvePartyUserName($this->sender, $this->senderCashRegister);
    }

    public function getReceiverUserNameAttribute(): ?string
    {
        return $this->resolvePartyUserName($this->receiver, $this->receiverCashRegister);
    }

    public function getSenderKindAttribute(): ?string
    {
        return $this->resolvePartyKind($this->sender_type, $this->sender_cash_register_id);
    }

    public function getReceiverKindAttribute(): ?string
    {
        return $this->resolvePartyKind($this->receiver_type, $this->receiver_cash_register_id);
    }

    private function resolvePartyName(?Model $party, ?CashRegister $legacy): ?string
    {
        return $party?->name ?? $legacy?->name;
    }

    private function resolvePartyUserName(?Model $party, ?CashRegister $legacy): ?string
    {
        $register = $party instanceof CashRegister ? $party : $legacy;

        return $register?->user?->name;
    }

    private function resolvePartyKind(?string $morphType, $legacyId): ?string
    {
        if ($morphType) {
            return class_basename($morphType);
        }

        return $legacyId ? 'CashRegister' : null;
    }

    /**
     * Mantiene sincronizadas las columnas polimórficas y las legacy
     * (sender_cash_register_id / receiver_cash_register_id).
     */
    private function syncPartyColumns(): void
    {
        foreach (['sender', 'receiver'] as $side) {
            $typeCol   = "{$side}_type";
            $idCol     = "{$side}_id";
            $legacyCol = "{$side}_cash_register_id";

            if ($this->{$typeCol} && $this->{$idCol}) {
                $this->{$legacyCol} = $this->{$typeCol} === CashRegister::class
                    ? $this->{$idCol}
                    : null;
            } elseif ($this->{$legacyCol}) {
                $this->{$typeCol} = CashRegister::class;
                $this->{$idCol}   = $this->{$legacyCol};
            }
        }
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

        static::saving(function (self $model) {
            $model->syncPartyColumns();
        });
    }
}
