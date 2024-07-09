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

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Description')->sortable(),
            Currency::make('Amount')->sortable(),
            Date::make('Date')->sortable(),
            Text::make('Payment Method')->sortable(),
            Text::make('Category')->sortable(),
            BelongsTo::make('Supplier', 'supplier', Supplier::class)->sortable(),
        ];
    }
}
