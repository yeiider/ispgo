<?php

namespace App\Nova\SupportTickets;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Color;

class LabelResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\Label::class;

    /**
     * El título que se usará para representar el recurso.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Los campos que se mostrarán en este recurso Nova.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Color::make('Color', 'color')
                ->rules('required'),

            HasMany::make('Tasks', 'tasks', TaskResourceNova::class),
        ];
    }
}
