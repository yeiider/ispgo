<?php

namespace App\Nova\Filters\Invoice;

use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoicePaymentDateFromFilter extends DateFilter
{
    public $component = 'date-filter';

    /**
     * Filtra facturas cuyo finalized_at (en additional_information) sea >= la fecha seleccionada.
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereRaw(
            "DATE(JSON_UNQUOTE(JSON_EXTRACT(additional_information, '$.finalized_at'))) >= ?",
            [$value]
        );
    }

    public function name()
    {
        return __('invoice.payment_date_from');
    }
}
