<?php

namespace App\Nova\Finance;

use App\Nova\Resource;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
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
            Text::make( __('attribute.description'),'description')->sortable(),
            Currency::make(__('attribute.amount'),'amount')->sortable(),
            Date::make(__('attribute.date'),'date')->sortable(),
            Text::make(__('attribute.payment_method'),'payment_method')->sortable(),
            Text::make(__('attribute.category'), 'category')->sortable()
        ];
    }
     public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
     {
        return __('income.incomes');
     }
}
