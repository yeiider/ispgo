<?php

namespace App\Nova\Credit;

use App\Models\Credit\CreditPayment as CreditPaymentModel;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreditPayment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Credit\CreditPayment>
     */
    public static $model = CreditPaymentModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'reference',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('credit.credit_account'), 'creditAccount', CreditAccount::class)
                ->withoutTrashed(),

            DateTime::make(__('credit.paid_at'), 'paid_at')
                ->required()
                ->sortable(),

            Currency::make(__('credit.amount'))
                ->required()
                ->min(0)
                ->step(0.01)
                ->displayUsing(fn ($value) => number_format($value, 2)),

            Select::make(__('credit.method'))
                ->options([
                    'cash' => __('credit.cash'),
                    'bank_transfer' => __('credit.bank_transfer'),
                    'credit_card' => __('credit.credit_card'),
                    'debit_card' => __('credit.debit_card'),
                    'check' => __('credit.check'),
                    'other' => __('credit.other'),
                ])
                ->displayUsingLabels()
                ->required(),

            Text::make(__('credit.reference'))
                ->nullable()
                ->help(__('credit.payment_reference')),

            Textarea::make(__('credit.notes'))
                ->nullable()
                ->alwaysShow(),

            HasMany::make(__('credit.account_entries'), 'accountEntries', AccountEntry::class),
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
        return [
            new Filters\PaymentMethodFilter,
        ];
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

    public static function label()
    {
        return __('credit.payments');
    }
}
