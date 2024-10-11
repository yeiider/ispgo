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

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Number::make(__('cash_register.initial_balance'), 'initial_balance')->sortable(),
            Number::make(__('cash_register.current_balance'), 'current_balance')->sortable(),
            DateTime::make(__('attribute.created_at'), 'created_at')->onlyOnDetail(),
            DateTime::make(__('attribute.updated_at'), 'updated_at')->onlyOnDetail(),
        ];
    }

    public static function label() {
        return __('cash_register.cash_registers');
    }
}
