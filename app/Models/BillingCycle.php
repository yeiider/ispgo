<?php

namespace App\Models;

use App\Models\Services\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCycle extends Model
{
    use HasFactory;

    protected $table = 'billing_cycles';

    protected $fillable = [
        'name',
        'billing_day',
        'suspension_day',
        'payment_due_day',
        'status',
    ];

    protected $casts = [
        'billing_day' => 'integer',
        'suspension_day' => 'integer',
        'payment_due_day' => 'integer',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'billing_cycle_id');
    }

    /**
     * Calculate due date based on payment_due_day and reference date.
     */
    public function calculateDueDate(?Carbon $fromDate = null): Carbon
    {
        $current = $fromDate ? $fromDate->copy() : now();
        $dueDay = $this->payment_due_day;
        $currentMonth = $current->month;
        $currentYear = $current->year;

        if ($dueDay < $current->day) {
            $dueMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
            $dueYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;
        } else {
            $dueMonth = $currentMonth;
            $dueYear = $currentYear;
        }

        // Clamp to valid days in month if needed
        $daysInMonth = Carbon::create($dueYear, $dueMonth, 1)->daysInMonth;
        $validDay = min($dueDay, $daysInMonth);

        return Carbon::create($dueYear, $dueMonth, $validDay, 0, 0, 0);
    }
}
