<?php

namespace App\Nova\Credit;

use App\Models\Credit\CreditAccount as CreditAccountModel;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class CreditAccount extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Credit\CreditAccount>
     */
    public static $model = CreditAccountModel::class;

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
        'id', 'customer.first_name', 'customer.last_name', 'customer.identity_document'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('credit.customer'), 'customer', \App\Nova\Customers\Customer::class)
                ->searchable()
                ->withoutTrashed(),

            Currency::make(__('credit.principal'))
                ->required()
                ->min(0)
                ->step(0.01)
                ->displayUsing(fn($value) => number_format($value, 2)),

            Number::make(__('credit.interest_rate'), 'interest_rate')
                ->required()
                ->min(0)
                ->max(100)
                ->step(0.01)
                ->help(__('credit.annual_interest_rate_percentage')),

            Number::make(__('credit.grace_period_days'), 'grace_period_days')
                ->required()
                ->min(0)
                ->default(0)
                ->help(__('credit.number_of_days_before_applying_penalties')),

            Select::make(__('credit.status'))
                ->options([
                    'active' => __('credit.active'),
                    'in_grace' => __('credit.in_grace'),
                    'overdue' => __('credit.overdue'),
                    'closed' => __('credit.closed'),
                ])
                ->displayUsingLabels()
                ->sortable(),

            Panel::make(__('credit.related_information'), [
                HasMany::make(__('credit.products'), 'products', CreditAccountProduct::class),
                HasMany::make(__('credit.installments'), 'installments', CreditInstallment::class),
                HasMany::make(__('credit.payments'), 'payments', CreditPayment::class),
            ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new Filters\CreditAccountStatus,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new Actions\RegisterPayment,
            new Actions\GrantGracePeriod,
        ];
    }

    public static function label()
    {
        return __('credit.credit_accounts');
    }
}
