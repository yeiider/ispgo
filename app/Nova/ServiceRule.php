<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class ServiceRule extends Resource
{
    public static $model = \App\Models\ServiceRule::class;


    public function fields(\Laravel\Nova\Http\Requests\NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Service'),
            Select::make('Type')->options([
                'percentage' => 'Descuento %',
                'fixed' => 'Descuento fijo',
                'free_month' => 'Mes gratis',
            ])->rules('required'),
            Number::make('Value')->hideWhenUpdating(fn() => $this->type === 'free_month'),
            Number::make('Cycles')->min(1)->rules('required', 'integer'),
            DateTime::make('Starts At')->nullable(),
            Number::make('Cycles Used')->exceptOnForms(),
        ];
    }
}
