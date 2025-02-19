<?php

namespace App\Nova\SupportTickets;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;

class ColumnResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\Column::class;

    /**
     * El título que se usará para representar el recurso.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * Los campos que se mostrarán en este recurso Nova.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Title', 'title')
                ->sortable()
                ->rules('required', 'max:255'),

            Number::make('Position', 'position')
                ->sortable()
                ->rules('required', 'integer'),

            BelongsTo::make('Board', 'board', BoardResourceNova::class)
                ->rules('required'),

            HasMany::make('Tasks', 'tasks', TaskResourceNova::class),
        ];
    }
}
