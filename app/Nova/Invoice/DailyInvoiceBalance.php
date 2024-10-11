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
    public function fields(NovaRequest $request): array
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
            Date::make(__('attribute.date'),'date')->sortable(),
            Currency::make(__('attribute.total_invoice'), 'total_invoice')->sortable(),
            Currency::make(__('attribute.paid_invoices'), 'paid_invoices')->sortable(),
            Currency::make(__('attribute.total_subtotal'), 'total_subtotal')->sortable(),
            Currency::make(__('attribute.total_tax'), 'total_tax')->sortable(),
            Currency::make(__('attribute.total_amount'), 'total_amount')->sortable(),
            Currency::make(__('attribute.total_discount'), 'total_discount')->sortable(),
            Currency::make(__('attribute.total_outstanding_balance'), 'total_outstanding_balance')->sortable(),
            Currency::make(__('attribute.total_revenue'), 'total_revenue')->sortable(),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('balance.daily_invoice_balance');
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
