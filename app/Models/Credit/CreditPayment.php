<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CreditPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'credit_account_id',
        'paid_at',
        'amount',
        'method',
        'reference',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global Scope: Filter by user's router through credit account -> customer
        static::addGlobalScope('router_filter', function (Builder $builder) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            
            // If not authenticated, no filtering
            if (!$user) {
                return;
            }

            // If super admin always sees all, or if no router assigned, show all
            if ($user->isSuperAdmin() || !$user->router_id) {
                return;
            }

            // Filter by router_id through credit account -> customer relationship (applies to admin with router_id and regular users with router_id)
            $builder->whereHas('creditAccount.customer', function ($query) use ($user) {
                $query->where('router_id', $user->router_id);
            });
        });
    }

    /**
     * Get the credit account that owns the payment.
     */
    public function creditAccount()
    {
        return $this->belongsTo(CreditAccount::class);
    }

    /**
     * Get all of the payment's account entries.
     */
    public function accountEntries()
    {
        return $this->morphMany(AccountEntry::class, 'creditable');
    }

    /**
     * Scope a query to only include payments made today.
     */
    public function scopePaidToday($query)
    {
        return $query->whereDate('paid_at', now());
    }

    /**
     * Scope a query to only include payments made this month.
     */
    public function scopePaidThisMonth($query)
    {
        return $query->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year);
    }

    /**
     * Scope a query to only include payments made by a specific method.
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get the formatted paid_at date.
     */
    public function getFormattedPaidAtAttribute()
    {
        return $this->paid_at->format('Y-m-d H:i:s');
    }
}
