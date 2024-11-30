<?php

namespace App\Nova\Invoice;

use App\Nova\Actions\Invoice\Invoice\RegisterPaymentByPromise;
use App\Nova\Customers\Customer;
use App\Nova\Filters\Invoice\PaymentPromiseStatus;
use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaymentPromise extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice\PaymentPromise::class;

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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('invoice.invoice'),'invoice',Invoice::class)->searchable(),
            BelongsTo::make(__('customer.customer'),'customer',Customer::class)->searchable(),
            BelongsTo::make(__('user.user'),'user',User::class)->default(Auth::id())->readonly(),
            Currency::make(__('invoice.amount'), 'amount')->step(0.01)->rules('required', 'numeric', 'min:0'),
            Badge::make(__('Status'), 'status')->map([
                'pending' => 'warning',
                'fulfilled' => 'success',
                'cancelled' => 'danger'
            ])->icons([
                'danger' => 'exclamation-triangle',
                'success' => 'check-circle',
                'warning' => 'clock',
            ])->label(function ($value) {
                return __('attribute.' . $value);
            }),
            Date::make(__('payment_promise.promise_date'), 'promise_date')->rules('required', 'date'),
            Text::make(__('payment_promise.notes'), 'notes')->rules('nullable', 'string', 'max:255'),
        ];
    }


    public function filters(Request $request): array
    {
        return [
            new PaymentPromiseStatus,
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new RegisterPaymentByPromise())->showInline()
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('payment_promise.payment_promise');
    }


    /**
     *
     */
    public static function authorizedToCreate(Request $request): bool
    {
        return auth()->check() && $request->user()->can('updatePaymentPromise');
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return auth()->check() && $request->user()->can('updatePaymentPromise', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deletePaymentPromise', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyPaymentPromise');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewPaymentPromise', $this->resource);
    }
}
