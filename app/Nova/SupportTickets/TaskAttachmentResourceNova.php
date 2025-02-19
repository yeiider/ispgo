<?php

namespace App\Nova\SupportTickets;

use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;

class TaskAttachmentResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\TaskAttachment::class;

    /**
     * El título que se usará para representar el recurso.
     *
     * @var string
     */
    public static $title = 'file_name';

    /**
     * Los campos que se mostrarán en este recurso Nova.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('File Name', 'file_name')
                ->sortable()
                ->rules('required', 'max:255'),

            File::make('File Path', 'file_path')
                ->hideFromIndex()
                ->rules('required'),

            BelongsTo::make('Task', 'task', TaskResourceNova::class)
                ->rules('required'),

        ];
    }
}
