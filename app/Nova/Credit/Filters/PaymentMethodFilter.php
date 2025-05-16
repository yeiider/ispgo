<?php

namespace App\Nova\Credit\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PaymentMethodFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('method', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Cash' => 'cash',
            'Bank Transfer' => 'bank_transfer',
            'Credit Card' => 'credit_card',
            'Debit Card' => 'debit_card',
            'Check' => 'check',
            'Other' => 'other',
        ];
    }
}
