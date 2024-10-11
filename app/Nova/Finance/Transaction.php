<?php

namespace App\Nova\Finance;

use App\Nova\Resource;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transaction extends Resource
{
    public static $model = \App\Models\Finance\Transaction::class;

    public static $title = 'description';

    public static $search = [
        'id', 'description', 'amount'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('attribute.description'),'description')->sortable(),
            Currency::make(__('attribute.amount'),'amount')->sortable(),
            Date::make(__('attribute.date'),'date')->sortable(),
            Text::make(__('attribute.type'),'type')->sortable(),
            Text::make(__('attribute.payment_method'),'payment_method')->sortable(),
            Text::make(__('attribute.category'),'category')->sortable(),
            BelongsTo::make(__('cash_register.cash_register'), 'cashRegister', CashRegister::class)->searchable(),
        ];
    }
}
