<?php

namespace App\Nova\SupportTickets;

use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;

class TaskCommentResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\TaskComment::class;

    /**
     * El título que se usará para representar el recurso.
     *
     * @var string
     */
    public static $title = 'content';

    /**
     * Los campos que se mostrarán en este recurso Nova.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Content', 'content')
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Task', 'task', TaskResourceNova::class)
                ->rules('required'),

            BelongsTo::make('User', 'user', User::class)
                ->rules('required'),
        ];
    }
}
