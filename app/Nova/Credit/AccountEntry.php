<?php

namespace App\Nova\Credit;

use App\Models\Credit\AccountEntry as AccountEntryModel;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class AccountEntry extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Credit\AccountEntry>
     */
    public static $model = AccountEntryModel::class;

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
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            MorphTo::make(__('credit.creditable'), 'Creditable')
                ->types([
                    CreditInstallment::class,
                    CreditPayment::class,
                ])
                ->searchable(),

            Select::make(__('credit.entry_type'), 'entry_type')
                ->options([
                    'debit' => __('credit.debit'),
                    'credit' => __('credit.credit'),
                ])
                ->displayUsingLabels()
                ->required(),

            Currency::make(__('credit.amount'))
                ->required()
                ->min(0)
                ->step(0.01)
                ->displayUsing(fn($value) => number_format($value, 2)),

            Currency::make(__('credit.balance_after'), 'balance_after')
                ->required()
                ->step(0.01)
                ->displayUsing(fn($value) => number_format($value, 2))
                ->readonly(),

            DateTime::make(__('credit.created_at'))
                ->sortable()
                ->readonly(),

            DateTime::make(__('credit.updated_at'))
                ->sortable()
                ->readonly()
                ->hideFromIndex(),
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
            new Filters\EntryTypeFilter,
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
        return [];
    }

    public static function label()
    {
        return __('credit.account_entries');
    }
}
