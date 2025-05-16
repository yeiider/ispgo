<?php

namespace App\Nova\Credit;

use App\Models\Credit\CreditInstallment as CreditInstallmentModel;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreditInstallment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Credit\CreditInstallment>
     */
    public static $model = CreditInstallmentModel::class;

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
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('credit.credit_account'), 'creditAccount', CreditAccount::class)
                ->withoutTrashed(),

            Date::make(__('credit.due_date'), 'due_date')
                ->required()
                ->sortable(),

            Currency::make(__('credit.amount_due'), 'amount_due')
                ->required()
                ->min(0)
                ->step(0.01)
                ->displayUsing(fn ($value) => number_format($value, 2)),

            Currency::make(__('credit.principal_portion'), 'principal_portion')
                ->required()
                ->min(0)
                ->step(0.01)
                ->displayUsing(fn ($value) => number_format($value, 2)),

            Currency::make(__('credit.interest_portion'), 'interest_portion')
                ->required()
                ->min(0)
                ->step(0.01)
                ->displayUsing(fn ($value) => number_format($value, 2)),

            Select::make(__('credit.status'))
                ->options([
                    'pending' => __('credit.pending'),
                    'paid' => __('credit.paid'),
                    'overdue' => __('credit.overdue'),
                ])
                ->displayUsingLabels()
                ->sortable(),

            HasMany::make(__('credit.account_entries'), 'accountEntries', AccountEntry::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new Filters\CreditInstallmentStatus,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function label()
    {
        return __('credit.installments');
    }
}
