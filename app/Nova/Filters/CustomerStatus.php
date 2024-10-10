<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CustomerStatus extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Customer Status';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('customer_status', $value);
    }

    /**
     * Get the options for the filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            __('attribute.active') => 'active',
            __('attribute.inactive') => 'inactive',
        ];
    }
}
