<?php

namespace App\Nova;

use App\Models\TicketLabel as TicketLabelModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TicketLabel extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = TicketLabelModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'description'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make(__('attribute.name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Color::make(__('attribute.color'), 'color')
                ->rules('required'),

            Text::make(__('attribute.description'), 'description')
                ->nullable()
                ->hideFromIndex(),

            Boolean::make(__('attribute.is_active'), 'is_active')
                ->sortable()
                ->default(true),

            BelongsToMany::make(__('attribute.tickets'), 'tickets', Ticket::class),

            DateTime::make(__('attribute.created_at'), 'created_at')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make(__('attribute.updated_at'), 'updated_at')
                ->onlyOnDetail()
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request): array
    {
        return [];
    }
}
