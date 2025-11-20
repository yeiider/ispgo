<?php

namespace App\Nova\Invoice;

use App\Nova\Actions\Invoice\ApplyDiscount;
use App\Nova\Actions\Invoice\DownloadInvoicePdf;
use App\Nova\Actions\Invoice\RegisterPayment;
use App\Nova\Actions\Invoice\RegisterPaymentPromise;
use App\Nova\Actions\Invoice\SendInvoiceByWhatsapp;
use App\Nova\Actions\Invoice\SendInvoiceNotification;
use App\Nova\Actions\Invoice\ProcessOnePayCharge;
use App\Nova\Actions\Invoice\DeleteOnePayCharge;
use App\Nova\Actions\Invoice\NotifyAllInvoices;
use App\Nova\Customers;
use App\Nova\Filters\Invoice\InvoiceStatusFilter;
use App\Nova\Filters\Invoice\InvoiceDateRangeFilter;
use App\Nova\Filters\Invoice\InvoiceDateToFilter;
use App\Nova\Filters\RouterFilter;
use App\Nova\Metrics\Invoice\InvoicesStatus;
use App\Nova\Metrics\Invoice\OutstandingBalance;
use App\Nova\Metrics\Invoice\TotalRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Invoice extends Resource
{
    public static $model = \App\Models\Invoice\Invoice::class;

    public static $title = 'invoice_full_name_descriptions';

    public static $search = [
        'id', 'amount', 'issue_date', 'status', 'customer_name'
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make(__('invoice.increment_id'), 'increment_id')->readonly(),
            BelongsTo::make(__('customer.customer'), 'customer', Customers\Customer::class)->searchable()->readonly(),
            BelongsTo::make(__('service.service'), 'service', \App\Nova\Service::class)->searchable()->readonly(),
            BelongsTo::make(__('Router (Zone)'), 'router', \App\Nova\Router::class)->searchable(),

            // 💰 Totales detallados
            Currency::make(__('invoice.amount_before_discounts'), 'amount_before_discounts')
                ->step(0.01)
                ->readonly()
                ->hideFromIndex(),

            Currency::make(__('invoice.discount'), 'discount')
                ->step(0.01)
                ->readonly(),

            Currency::make(__('invoice.subtotal'), 'subtotal')
                ->step(0.01)
                ->readonly(),

            Currency::make(__('invoice.tax_total'), 'tax_total')
                ->step(0.01)
                ->readonly()
                ->hideFromIndex(),

            Currency::make(__('invoice.void_total'), 'void_total')
                ->step(0.01)
                ->readonly()
                ->hideFromIndex(),

            Currency::make(__('invoice.total'), 'total')
                ->step(0.01)
                ->readonly(),

            Currency::make(__('invoice.outstanding_balance'), 'outstanding_balance')
                ->step(0.01)
                ->readonly(),

            Currency::make(__('invoice.amount'), 'amount')  // ¿Este es un campo manual de abono o pago?
            ->step(0.01)
                ->readonly(),

            // 📆 Fechas y estado
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
            ])->label(fn($value) => __('attribute.' . $value)),

            Text::make(__('invoice.payment_method'), 'payment_method'),
            Textarea::make(__('invoice.notes'), 'notes')->hideFromIndex(),

            // Evidence and additional info
            URL::make(__('Payment Evidence'), function () {
                return $this->payment_support ? Storage::disk('public')->url($this->payment_support) : null;
            })->onlyOnDetail(),

            KeyValue::make(__('invoice.additional_information'), 'additional_information')
                ->hideFromIndex()
                ->readonly(),

            // OnePay info
            URL::make('onepay_payment_link', function () {
                return $this->onepay_payment_link;
            })->onlyOnDetail(),
            Text::make('onepay_status', 'onepay_status')->onlyOnDetail(),
        ];

    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new RegisterPayment())->showInline(),
            (new RegisterPaymentPromise())->showInline(),
            (new ApplyDiscount())->showInline(),
            DestructiveAction::using(__('invoice.cancel_invoice'), function (ActionFields $fields, Collection $models) {
                $models->each->canceled();
            })->showInline(),
            Action::openInNewTab(__('invoice.view_invoice'), function ($invoice) {
                return route('preview.invoice', $invoice->increment_id);
            })->sole(),
            Action::openInNewTab(__('invoice.view_receipt'), function ($invoice) {
                return route('preview.receipt', $invoice->increment_id);
            })->canRun(function ($request, $invoice) {
                return $invoice->status === 'paid';
            })->sole(),
            (new SendInvoiceByWhatsapp())->showInline(),
            (new SendInvoiceNotification())->showInline(),
            (new NotifyAllInvoices())->standalone(),
            // OnePay actions
            (new ProcessOnePayCharge())
                ->canSee(function ($request) {
                    // Visible always; label adapts depending on state handled internally
                    return true;
                })
                ->showInline(),
            (new DeleteOnePayCharge())
                ->canSee(function ($request) {
                    return (bool) optional($this->resource)->onepay_charge_id;
                })
                ->showInline(),
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
            new InvoiceDateRangeFilter(),
            new InvoiceDateToFilter(),
            new RouterFilter(),
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
