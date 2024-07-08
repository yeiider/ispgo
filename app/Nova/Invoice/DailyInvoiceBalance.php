<?php

namespace App\Nova\Invoice;

use App\Nova\Resource;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class DailyInvoiceBalance extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Invoice\DailyInvoiceBalance>
     */
    public static $model = \App\Models\Invoice\DailyInvoiceBalance::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        /**
         * 'date' => $date,
         * 'total_invoices' => $totalInvoices,
         * 'paid_invoices' => $paidInvoices,
         * 'total_subtotal' => $totalSubtotal,
         * 'total_tax' => $totalTax,
         * 'total_amount' => $totalAmount,
         * 'total_discount' => $totalDiscount,
         * 'total_outstanding_balance' => $totalOutstandingBalance,
         * 'total_revenue' => $totalRevenue,
         **/

        return [
            ID::make()->sortable(),
            Date::make('Date')->sortable(),
            Currency::make('Total Invoices')->sortable(),
            Currency::make('Paid Invoices')->sortable(),
            Currency::make('Total Subtotal')->sortable(),
            Currency::make('Total Tax')->sortable(),
            Currency::make('Total Amount')->sortable(),
            Currency::make('Total Discount')->sortable(),
            Currency::make('Total Outstanding Balance')->sortable(),
            Currency::make('Total Revenue')->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
