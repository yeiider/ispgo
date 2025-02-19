<?php

namespace App\Nova\SupportTickets;

use App\Nova\Customers\Customer;
use App\Nova\Resource;
use App\Nova\Service;
use Illuminate\Http\Request;
use Ispgo\Ckeditor\Ckeditor;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;

class TaskResourceNova extends Resource
{
    /**
     * El modelo asociado con este recurso.
     *
     * @var string
     */
    public static $model = \App\Models\SupportTickets\Task::class;

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
            Text::make('Title', 'title')
                ->sortable()
                ->rules('required', 'max:255'),

            Ckeditor::make('Description', 'description')
                ->hideFromIndex()
                ->rules('required'),

            BelongsTo::make('Column', 'column', ColumnResourceNova::class)
                ->rules('required'),


            BelongsTo::make('Customer', 'customer', Customer::class),

            BelongsTo::make('Service', 'service', Service::class),

            DateTime::make('Due Date', 'due_date')
                ->sortable(),

            Select::make('Priority', 'priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])
                ->sortable()
                ->rules('required'),

            HasMany::make('Comments', 'comments', TaskCommentResourceNova::class),

            HasMany::make('Attachments', 'attachments', TaskAttachmentResourceNova::class),

            HasMany::make('Labels', 'labels', LabelResourceNova::class),
        ];
    }
}
