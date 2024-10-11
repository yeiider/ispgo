<?php

namespace App\Nova\Inventory;

use App\Nova\Filters\Inventory\EquipmentAssignmentStatus;
use App\Nova\Resource;
use App\Nova\User;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class EquipmentAssignment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Inventory\EquipmentAssignment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('User'), 'user', User::class)->sortable()->searchable(),
            BelongsTo::make(__('Product'), 'product', Product::class)->sortable()->searchable(),
            DateTime::make(__('Assigned At'), 'assigned_at')->sortable(),
            DateTime::make(__('Returned At'), 'returned_at')->nullable()->sortable(),
            Select::make('Status', 'status')->options([
                'assigned' => __('Assigned'),
                'returned' => __('Returned'),
            ])->displayUsingLabels()->sortable(),
            Text::make(__('Condition on Assignment'), 'condition_on_assignment')->nullable(),
            Text::make(__('Condition on Return'), 'condition_on_return')->nullable(),
            Textarea::make(__('Notes'), 'notes')->nullable(),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Equipment Assignments');
    }


    public static function singularLabel(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Equipment Assignment');
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [
            new EquipmentAssignmentStatus
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
