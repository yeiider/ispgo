<?php

namespace App\Nova;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;

class Router extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Router>
     */
    public static $model = \App\Models\Router::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Select::make('Status', 'status')
                ->options([
                    'enabled' => 'Enabled',
                    'disabled' => 'Disabled',
                ])->default("enabled"),

            Badge::make('status','status')->map([
                'enabled' => 'success',
                'disabled' => 'danger',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
            ]),
            Text::make(__('Code'), 'code')
                ->rules('required', 'max:255')
                ->sortable(),

            Text::make(__('Name'), 'name')
                ->rules('required', 'max:255')
                ->sortable(),
        ];
    }


    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
