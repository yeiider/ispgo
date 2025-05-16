<?php

namespace App\Nova\Credit\Metrics;

use App\Models\Credit\CreditAccount;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class CreditAccountsByStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, CreditAccount::class, 'status')
            ->label(function ($value) {
                return match ($value) {
                    'active' => 'Active',
                    'in_grace' => 'In Grace Period',
                    'overdue' => 'Overdue',
                    'closed' => 'Closed',
                    default => ucfirst($value),
                };
            })
            ->colors([
                'active' => '#38c172',     // Green
                'in_grace' => '#ffed4a',   // Yellow
                'overdue' => '#e3342f',    // Red
                'closed' => '#6c757d',     // Gray
            ]);
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // Cache for 1 hour in production
        return app()->environment('production') ? now()->addHour() : null;
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'credit-accounts-by-status';
    }

    /**
     * Get the displayable name of the metric.
     *
     * @return string
     */
    public function name()
    {
        return 'Credit Accounts by Status';
    }
}
