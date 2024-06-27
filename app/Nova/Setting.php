<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class Setting extends Resource
{
    public static string $model = \App\Models\Setting::class;

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Section')->sortable()->rules('required', 'max:255'),
            Text::make('Group')->sortable()->rules('required', 'max:255'),
            Text::make('Key')->sortable()->rules('required', 'max:255'),
            Textarea::make('Value')->rules('required'),
        ];
    }
}
