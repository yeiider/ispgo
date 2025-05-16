<?php

namespace App\Models\Credit;

use App\Models\Customers\Customer;
use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'principal',
        'interest_rate',
        'grace_period_days',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'principal' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'grace_period_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the credit account.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the products associated with the credit account.
     */
    public function products()
    {
        return $this->hasMany(CreditAccountProduct::class);
    }

    /**
     * Get the installments for the credit account.
     */
    public function installments()
    {
        return $this->hasMany(CreditInstallment::class);
    }

    /**
     * Get the payments for the credit account.
     */
    public function payments()
    {
        return $this->hasMany(CreditPayment::class);
    }

    /**
     * Get the account entries for the credit account through installments and payments.
     */
    public function accountEntries()
    {
        return $this->hasManyThrough(
            AccountEntry::class,
            CreditInstallment::class,
            'credit_account_id',
            'creditable_id'
        )->where('creditable_type', CreditInstallment::class);
    }

    /**
     * Get the total amount paid for this credit account.
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Get the remaining balance for this credit account.
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->principal - $this->total_paid;
    }

    /**
     * Get the payment progress as a percentage.
     */
    public function getPaymentProgressAttribute()
    {
        if ($this->principal <= 0) {
            return 100;
        }

        return min(100, round(($this->total_paid / $this->principal) * 100, 2));
    }

    /**
     * Get the next due installment.
     */
    public function getNextDueInstallmentAttribute()
    {
        return $this->installments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();
    }

    /**
     * Scope a query to only include active credit accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include overdue credit accounts.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope a query to only include accounts in grace period.
     */
    public function scopeInGrace($query)
    {
        return $query->where('status', 'in_grace');
    }
}
