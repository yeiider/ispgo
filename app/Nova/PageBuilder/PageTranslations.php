<?php

namespace App\Nova\PageBuilder;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class PageTranslations extends Resource
{
    public static $model = \App\Models\PageBuilder\PageTranslation::class;

    public static $title = 'title';

    public static $search = [
        'title',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Page', 'page', Pages::class),
            Select::make('Locale')->options([
                'es' => 'es_ES',
                'en' => 'en_US',
            ])->rules('required')->default('en'),
            Text::make('Title')->rules('required'),
            Text::make('Meta Title')->rules('required'),
            Text::make('Meta Description')->rules('required'),
            Text::make('Route')->rules('required'),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [
            Action::using(__('Go to page'), function (ActionFields $fields, Collection $models) {
                $model = $models->first();
                return ActionResponse::openInNewTab($model->route);
            })->showInline()
        ];
    }
}
