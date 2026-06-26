<?php

namespace App\Nova\Filters\Invoice;

use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoicePaymentDateFilter extends DateFilter
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
     * Filtra facturas cuyo campo JSON additional_information->finalized_at
     * coincida con la fecha seleccionada (solo Y-m-d, ignorando la hora).
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value  Formato Y-m-d entregado por el date-picker de Nova
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        // JSON_UNQUOTE + DATE() para extraer solo la parte de fecha del ISO 8601
        // y comparar con el valor seleccionado (Y-m-d).
        return $query->whereRaw(
            "DATE(JSON_UNQUOTE(JSON_EXTRACT(additional_information, '$.finalized_at'))) = ?",
            [$value]
        );
    }

    /**
     * Get the filter's name.
     *
     * @return string
     */
    public function name()
    {
        return __('invoice.payment_date');
    }
}
