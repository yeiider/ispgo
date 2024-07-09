<?php

namespace App\Nova\Finance;

use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class CashRegister extends Resource
{
    public static $model = \App\Models\Finance\CashRegister::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('Initial Balance', 'initial_balance')->sortable(),
            Number::make('Current Balance', 'current_balance')->sortable(),
            DateTime::make('Created At', 'created_at')->onlyOnDetail(),
            DateTime::make('Updated At', 'updated_at')->onlyOnDetail(),
        ];
    }
}
