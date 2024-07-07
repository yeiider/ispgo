<?php

namespace App\Nova;

use App\Nova\Actions\RegisterPayment;
use App\Nova\Actions\RegisterPaymentPromise;
use App\Nova\Filters\InvoiceStatusFilter;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;

class Invoice extends Resource
{
    public static $model = \App\Models\Invoice::class;

    public static $title = 'id';

    public static $search = [
        'id', 'amount', 'issue_date', 'status'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Customer', 'customer', \App\Nova\Customer::class)->searchable(),
            BelongsTo::make('Service', 'service', \App\Nova\Service::class)->searchable(),

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
                'overdue' => 'Overdue'
            ])->displayUsingLabels()->hideFromIndex(),
            Badge::make(__('Status'))->map([
                'paid' => 'success',
                'overdue' => 'danger',
                'unpaid' => 'warning',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
                'warning' => 'minus-circle',
            ]),
            Text::make('Payment Method', 'payment_method'),
            Textarea::make('Notes', 'notes')->hideFromIndex(),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            new RegisterPayment(),
            new RegisterPaymentPromise()
        ];
    }

    public function filters(Request $request)
    {
        return [
            new InvoiceStatusFilter(),
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
