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

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('invoice.increment_id'), 'increment_id')->readonly(),
            BelongsTo::make(__('customer.customer'), 'customer', Customers\Customer::class)->searchable()->readonly(),
            BelongsTo::make(__('service.service'), 'service', \App\Nova\Service::class)->searchable()->readonly(),

            Currency::make(__('invoice.subtotal'), 'subtotal')->step(0.01),
            Currency::make(__('invoice.tax'), 'tax')->step(0.01),
            Currency::make(__('invoice.total'), 'total')->step(0.01),
            Currency::make(__('invoice.amount'), 'amount')->step(0.01),
            Currency::make(__('invoice.discount'), 'discount')->step(0.01),
            Currency::make(__('invoice.total_pay'), 'total')->step(0.01),
            Date::make(__('invoice.issue_date'), 'issue_date'),
            Date::make(__('invoice.due_date'), 'due_date'),
            Select::make(__('attribute.status'), 'status')->options([
                'paid' => __('attribute.paid'),
                'unpaid' => __('attribute.unpaid'),
                'overdue' => __('attribute.overdue'),
                'canceled' => __('attribute.canceled')
            ])->displayUsingLabels()
                ->hideFromIndex()
                ->hideFromDetail()
                ->readonly(),

            Badge::make(__('attribute.status'), 'status')->map([
                'paid' => 'success',
                'overdue' => 'info',
                'unpaid' => 'warning',
                'canceled' => 'danger'
            ])->icons([
                'danger' => 'x-circle',
                'success' => 'check-circle',
                'warning' => 'minus-circle',
                'info' => 'speakerphone'
            ])->label(function ($value) {
                return __('attribute.'.$value);
            }),
            Text::make(__('invoice.payment_method'), 'payment_method'),
            Textarea::make(__('invoice.notes'), 'notes')->hideFromIndex(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new RegisterPayment())->showInline(),
            (new RegisterPaymentPromise())->showInline(),
            (new ApplyDiscount())->showInline(),
            (new DownloadInvoicePdf())->showInline(),
            DestructiveAction::using(__('invoice.cancel_invoice'), function (ActionFields $fields, Collection $models) {
                $models->each->canceled();
            })->showInline()
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('invoice.invoice');
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
