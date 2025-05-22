<?php

namespace App\Nova;

use App\Models\TicketAttachment as TicketAttachmentModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TicketAttachment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = TicketAttachmentModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'original_filename';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'original_filename', 'filename'
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

            BelongsTo::make(__('attribute.ticket'), 'ticket', Ticket::class)
                ->sortable()
                ->rules('required'),


            Text::make(__('attribute.filename'), 'filename')
                ->sortable()
                ->rules('required'),



            File::make(__('attribute.file'), 'file_path')
                ->disk('public')
                ->path('ticket-attachments')
                ->prunable()
                ->rules('required'),


            BelongsTo::make(__('attribute.uploaded_by'), 'uploader', User::class)
                ->sortable()
                ->rules('required'),

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
