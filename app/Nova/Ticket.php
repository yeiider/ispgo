<?php

namespace App\Nova;

use App\Models\User;
use App\Nova\Actions\AssignmentTickets;
use App\Nova\Customers\Customer;
use App\Nova\Filters\PriorityTickets;
use App\Nova\Filters\StatusTickets;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;

use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
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
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('customer.customer'), 'customer', Customer::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            Select::make(__('service.service'), 'service_id')
                ->sortable()
                ->nullable()
                ->dependsOn(['customer'], function ($field, NovaRequest $request, $formData) {
                    if (isset($formData['customer'])) {
                        $customers = \App\Models\Customers\Customer::find($formData['customer']);
                        $customerservices = $customers->services;
                        $field->readonly(false);
                        $field->options($customerservices->pluck('full_service_name', 'id'));
                    } else {
                        $field->options([]);
                        $field->readonly(true);
                    }
                })->displayUsingLabels(),


            Select::make(__('attribute.issue_type'), 'issue_type')
                ->options([
                    'connectivity' => __('attribute.connectivity'),
                    'billing' => __('attribute.billing'),
                    'configuration' => __('attribute.configuration'),
                    'installation' => __('attribute.installation'),
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Select::make(__('attribute.priority'), 'priority')
                ->options([
                    'low' => __('attribute.low'),
                    'medium' => __('attribute.medium'),
                    'high' => __('attribute.high'),
                    'urgent' => __('attribute.urgent'),
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Select::make(__('attribute.status'), 'status')
                ->options([
                    'open' => __('attribute.open'),
                    'in_progress' => __('attribute.in_progress'),
                    'resolved' => __('attribute.resolved'),
                    'closed' => __('attribute.closed'),
                ])
                ->displayUsingLabels()
                ->default('open')
                ->rules('required'),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Markdown::make(__('attribute.description'), 'description')
                ->alwaysShow()
                ->rules('required'),

            DateTime::make(__('attribute.created_at'), 'created_at')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make(__('attribute.updated_at'), 'updated_at')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make(__('attribute.closed_at'), 'closed_at')
                ->nullable()
                ->onlyOnDetail()
                ->sortable(),

            Tag::make(__('attribute.technicians'), 'users', \App\Nova\User::class)
                ->searchable()
                ->showCreateRelationButton(),

            Markdown::make(__('attribute.resolution_notes'), 'resolution_notes')
                ->alwaysShow()
                ->nullable(),

            File::make(__('attribute.attachments'), 'attachments')
                ->nullable(),

            Text::make(__('attribute.contact_method'), 'contact_method')
                ->nullable()
                ->sortable(),

            HasMany::make(__('attribute.comments'), 'comments', TicketComment::class),

            HasMany::make(__('attribute.attachments'), 'attachments', TicketAttachment::class),

            Select::make(__('attribute.labels'), 'labels')
                ->options([
                    'support' => __('attribute.support'),
                    'billing' => __('attribute.billing'),
                    'technical' => __('attribute.technical'),
                    'installation' => __('attribute.installation'),
                    'other' => __('attribute.other'),
                ]),


            DateTime::make('Created At')->filterable()->onlyOnIndex(),

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
        return [
            (new AssignmentTickets())->showInline(),
        ];
    }
}
