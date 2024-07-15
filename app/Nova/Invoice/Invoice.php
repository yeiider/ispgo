<?php

namespace App\Nova\Invoice;

use App\Nova\Actions\ApplyDiscount;
use App\Nova\Actions\DownloadInvoicePdf;
use App\Nova\Actions\Invoice\RegisterPayment;
use App\Nova\Actions\Invoice\RegisterPaymentPromise;
use App\Nova\Customers;
use App\Nova\Filters\Invoice\InvoiceStatusFilter;
use App\Nova\Metrics\Invoice\InvoicesStatus;
use App\Nova\Metrics\Invoice\MonthlyRevenue;
use App\Nova\Metrics\Invoice\OutstandingBalance;
use App\Nova\Metrics\Invoice\TotalRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Invoice extends Resource
{
    public static $model = \App\Models\Invoice\Invoice::class;

    public static $title = 'id';

    public static $search = [
        'id', 'amount', 'issue_date', 'status'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Customer', 'customer', Customers\Customer::class)->searchable()->readonly(),
            BelongsTo::make('Service', 'service', \App\Nova\Service::class)->searchable()->readonly(),

            Currency::make('Subtotal', 'subtotal')->step(0.01),
            Currency::make('Tax', 'tax')->step(0.01),
            Currency::make('Total', 'total')->step(0.01),
            Currency::make('Amount', 'amount')->step(0.01),
            Currency::make('Discount', 'discount')->step(0.01),
            Currency::make('Total Pay', 'total')->step(0.01),
            Date::make('Issue Date', 'issue_date'),
            Date::make('Due Date', 'due_date'),
            Select::make('Status', 'status')->options([
                'paid' => 'Paid',
                'unpaid' => 'Unpaid',
                'overdue' => 'Overdue',
                'canceled' => 'Canceled'
            ])->displayUsingLabels()->hideFromIndex()->readonly(),
            Badge::make(__('Status'))->map([
                'paid' => 'success',
                'overdue' => 'info',
                'unpaid' => 'warning',
                'canceled' => 'danger'
            ])->icons([
                'danger' => 'x-circle',
                'success' => 'check-circle',
                'warning' => 'minus-circle',
                'info' => 'speakerphone'
            ]),
            Text::make('Payment Method', 'payment_method'),
            Textarea::make('Notes', 'notes')->hideFromIndex(),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            (new RegisterPayment())->showInline(),
            (new RegisterPaymentPromise())->showInline(),
            (new ApplyDiscount())->showInline(),
            (new DownloadInvoicePdf())->showInline(),
            DestructiveAction::using(__('Cancel invoice'), function (ActionFields $fields, Collection $models) {
                $models->each->canceled();
            })->showInline()
        ];
    }

    public function filters(Request $request)
    {
        return [
            new InvoiceStatusFilter(),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            new InvoicesStatus(),
            new OutstandingBalance(),
            new TotalRevenue(),
          //  new MonthlyRevenue()
        ];
    }
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateInvoice', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteInvoice', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyInvoice');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewInvoice', $this->resource);
    }

}
