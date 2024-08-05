<?php

namespace App\Nova;

use App\Models\User;
use App\Nova\Actions\AssignmentTickets;
use App\Nova\Customers\Customer;
use App\Nova\Filters\PriorityTickets;
use App\Nova\Filters\StatusTickets;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Ticket extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Ticket::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'description'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(Request $request)
    {
        $technicians = User::technicians()->pluck('name', 'id');
        return [
            ID::make()->sortable(),

            BelongsTo::make('Customer', 'customer', Customer::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            BelongsTo::make('Service', 'service', Service::class)
                ->sortable()
                ->searchable()
                ->nullable(),

            Select::make('Issue Type')
                ->options([
                    'connectivity' => 'Connectivity',
                    'billing' => 'Billing',
                    'configuration' => 'Configuration',
                    // Add more issue types as needed
                ])
                ->rules('required'),

            Select::make('Priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ])
                ->rules('required'),

            Select::make('Status')
                ->options([
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ])
                ->default('open')
                ->rules('required'),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->alwaysShow()
                ->rules('required'),

            DateTime::make('Created At')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make('Updated At')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make('Closed At')
                ->nullable()
                ->onlyOnDetail()
                ->sortable(),

            Select::make('Technician', 'user_id')
                ->options($technicians)
                ->displayUsingLabels(),

            Textarea::make('Resolution Notes')
                ->alwaysShow()
                ->nullable(),

            File::make('Attachments')
                ->nullable(),

            Text::make('Contact Method')
                ->nullable()
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new StatusTickets,
            new PriorityTickets,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request)
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
        return [
            (new AssignmentTickets())->showInline(),
        ];
    }
}
