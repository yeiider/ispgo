<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditInstallment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'credit_account_id',
        'due_date',
        'amount_due',
        'interest_portion',
        'principal_portion',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'amount_due' => 'decimal:2',
        'interest_portion' => 'decimal:2',
        'principal_portion' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the credit account that owns the installment.
     */
    public function creditAccount()
    {
        return $this->belongsTo(CreditAccount::class);
    }

    /**
     * Get all of the installment's account entries.
     */
    public function accountEntries()
    {
        return $this->morphMany(AccountEntry::class, 'creditable');
    }

    /**
     * Scope a query to only include pending installments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid installments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include overdue installments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope a query to only include installments due today or earlier.
     */
    public function scopeDueToday($query)
    {
        return $query->where('due_date', '<=', now()->format('Y-m-d'))
            ->where('status', 'pending');
    }

    /**
     * Check if the installment is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'overdue' ||
            ($this->status === 'pending' && $this->due_date < now()->format('Y-m-d'));
    }

    /**
     * Get the days overdue.
     */
    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }

    /**
     * Mark the installment as paid.
     */
    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->save();

        return $this;
    }

    /**
     * Mark the installment as overdue.
     */
    public function markAsOverdue()
    {
        $this->status = 'overdue';
        $this->save();

        return $this;
    }
}
