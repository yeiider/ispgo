<?php

namespace App\Nova\Filters\Invoice;

use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoiceDateRangeFilter extends DateFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'date-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereDate('issue_date', '>=', $value);
    }

    /**
     * Get the filter's name.
     *
     * @return string
     */
    public function name()
    {
        return __('invoice.issue_date_from');
    }
}
