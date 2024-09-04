<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class DailyBox extends Resource
{
    public static $model = \App\Models\DailyBox::class;

    public static $title = 'date';

    public static $search = [
        'id', 'date'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Box', 'box', Box::class)
                ->sortable()
                ->rules('required'),

            Date::make('Date')
                ->sortable()
                ->rules('required'),

            Number::make('Start Amount', 'start_amount')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),

            Number::make('End Amount', 'end_amount')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),

            Textarea::make('Transactions')
                ->hideFromIndex(),
        ];
    }

    public static function label()
    {
        return 'Daily Boxes';
    }

    public static function singularLabel()
    {
        return 'Daily Box';
    }
}

