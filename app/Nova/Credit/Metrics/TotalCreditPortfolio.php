<?php

namespace App\Nova\Credit\Metrics;

use App\Models\Credit\CreditAccount;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalCreditPortfolio extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        // Use model query builder to respect global scopes (including router filter)
        $creditAccounts = CreditAccount::where('status', '!=', 'closed')->get();
        
        // Calculate total principal
        $totalPrincipal = $creditAccounts->sum('principal');

        // Get total paid from payments
        $totalPaid = $creditAccounts->sum(function ($account) {
            return $account->payments->sum('amount') ?? 0;
        });

        $totalPortfolio = $totalPrincipal - $totalPaid;

        return $this->currency($totalPortfolio);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'ALL' => 'All Time',
        ];
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
        return 'total-credit-portfolio';
    }

    /**
     * Get the displayable name of the metric.
     *
     * @return string
     */
    public function name()
    {
        return 'Total Credit Portfolio';
    }
}
