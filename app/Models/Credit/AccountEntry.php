<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'entry_type',
        'amount',
        'balance_after',
        'creditable_id',
        'creditable_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent creditable model (CreditInstallment or CreditPayment).
     */
    public function creditable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include debit entries.
     */
    public function scopeDebit($query)
    {
        return $query->where('entry_type', 'debit');
    }

    /**
     * Scope a query to only include credit entries.
     */
    public function scopeCredit($query)
    {
        return $query->where('entry_type', 'credit');
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get the formatted balance after.
     */
    public function getFormattedBalanceAfterAttribute()
    {
        return '$' . number_format($this->balance_after, 2);
    }

    /**
     * Get the credit account associated with this entry.
     */
    public function getCreditAccountAttribute()
    {
        if ($this->creditable_type === CreditInstallment::class) {
            return $this->creditable->creditAccount;
        } elseif ($this->creditable_type === CreditPayment::class) {
            return $this->creditable->creditAccount;
        }

        return null;
    }

    /**
     * Get the customer associated with this entry.
     */
    public function getCustomerAttribute()
    {
        $creditAccount = $this->credit_account;

        return $creditAccount ? $creditAccount->customer : null;
    }
}
