<?php

namespace App\Nova\Filters\Invoice;

use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoicePaymentDateToFilter extends DateFilter
{
    public $component = 'date-filter';

    /**
     * Filtra facturas cuyo payment_date sea <= la fecha seleccionada.
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereDate('payment_date', '<=', $value);
    }

    public function name()
    {
        return __('invoice.payment_date_to');
    }
}
