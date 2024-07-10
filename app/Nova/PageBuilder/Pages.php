<?php

namespace App\Nova\PageBuilder;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Pages extends Resource
{
    public static $model = \App\Models\PageBuilder\Pages::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->rules('required'),
            Text::make('Layout')->rules('required')->default('master'),
            HasMany::make('Translations', 'translations', PageTranslations::class),
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
            Action::using(__('modify page content'), function (ActionFields $fields, Collection $models) {
                $model = $models->first();
                 return  ActionResponse::openInNewTab('/admin/pagebuilder?page=' . $model->id);
            })->showInline()
        ];
    }
}
