<?php

namespace App\Nova\Finance;

use App\Nova\Customers\Customer;
use App\Nova\Invoice\Invoice;
use App\Nova\Resource;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Income extends Resource
{
    public static $model = \App\Models\Finance\Income::class;

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
            BelongsTo::make('Customer', 'customer', Customer::class)->sortable(),
            BelongsTo::make('Invoice', 'invoice', Invoice::class)->sortable(),
        ];
    }
}