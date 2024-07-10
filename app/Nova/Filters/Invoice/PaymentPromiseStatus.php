<?php

namespace App\Nova\Filters\Invoice;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PaymentPromiseStatus extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Payment Promise Status';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param Request $request
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('status', $value);
    }

    /**
     * Get the options for the filter.
     *
     * @param Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Pending' => 'pending',
            'Fulfilled' => 'fulfilled',
            'Cancelled' => 'cancelled',
        ];
    }
}
