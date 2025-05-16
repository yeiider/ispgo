<?php

namespace App\Nova\Credit\Metrics;

use App\Models\Credit\CreditPayment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class PaymentsTrend extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sumByDays($request, CreditPayment::class, 'amount', 'paid_at')
            ->showSumValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7 => '7 Days',
            14 => '14 Days',
            30 => '30 Days',
            60 => '60 Days',
            90 => '90 Days',
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
        return 'payments-trend';
    }

    /**
     * Get the displayable name of the metric.
     *
     * @return string
     */
    public function name()
    {
        return 'Payments Trend';
    }
}
