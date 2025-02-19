<?php

namespace App\Nova\SupportTickets;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class BoardResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\Board::class;

    /**
     * El título que se usará para representar el recurso.
     *
     * @var string
     */
    public static $title = 'name';

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
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Description', 'description')
                ->hideFromIndex(),

            HasMany::make('Columns', 'columns', ColumnResourceNova::class),
        ];
    }
}
