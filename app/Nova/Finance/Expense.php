<?php

namespace App\Nova\Finance;

use App\Nova\Inventory\Supplier;
use App\Nova\Resource;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Expense extends Resource
{
    public static $model = \App\Models\Finance\Expense::class;

    public static $title = 'description';

    public static $search = [
        'id', 'description', 'amount'
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('expense.description'), 'description')->sortable(),
            Currency::make(__('expense.amount'), 'amount')->sortable(),
            Date::make(__('expense.date'), 'date')->sortable(),
            Text::make('expense.payment_method', 'payment_method')->sortable(),
            Text::make(__('expense.category'), 'category')->sortable(),
            BelongsTo::make(__('expense.supplier'), 'supplier', Supplier::class)->sortable(),
        ];
    }
}
