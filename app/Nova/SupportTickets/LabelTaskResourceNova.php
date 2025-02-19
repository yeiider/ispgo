<?php

namespace App\Nova\SupportTickets;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;

class LabelTaskResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\LabelTask::class;

    /**
     * El título que se usará para representar el recurso.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Los campos que se mostrarán en este recurso Nova.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Label', 'label', LabelResourceNova::class)
                ->rules('required'),

            BelongsTo::make('Task', 'task', TaskResourceNova::class)
                ->rules('required'),
        ];
    }
}
