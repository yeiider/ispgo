<?php

namespace App\Nova;

use App\Nova\Actions\RegisterPaymentPromise;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaymentPromise extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PaymentPromise::class;

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
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Invoice')->searchable(),
            BelongsTo::make('Customer')->searchable(),
            BelongsTo::make('User')->default(Auth::id())->readonly(),
            Currency::make('Amount')->step(0.01)->rules('required', 'numeric', 'min:0'),
            Date::make('Promise Date')->rules('required', 'date'),
            Text::make('Notes')->rules('nullable', 'string', 'max:255'),
        ];
    }



    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('updatePaymentPromise');
    }

    public function authorizedToUpdate(Request $request)
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
