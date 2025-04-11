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

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('box.box'), 'box', Box::class)
                ->sortable()
                ->rules('required'),

            Date::make(__('attribute.date'), 'date')
                ->sortable()
                ->rules('required'),

            Number::make(__('box.start_amount'), 'start_amount')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),

            Number::make(__('box.end_amount'), 'end_amount')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),

            /*Textarea::make(__('attribute.transactions'), 'transactions')
                ->hideFromIndex(),*/
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('box.daily_boxes');
    }

    public static function singularLabel(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('box.daily_box');
    }
}

