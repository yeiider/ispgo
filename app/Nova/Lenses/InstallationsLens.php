<?php

namespace App\Nova\Lenses;

use App\Models\Action;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class InstallationsLens extends Lens
{


    /**
     * Get the name of the lens.
     *
     * @return string
     */

    public function name()
    {
        return __('Install');
    }

    /**
     * Get the query builder for the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function query(LensRequest $request, $query)
    {
        return $query->where('action_type', 'installation');
    }

    /**
     * Get the fields available to the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Service')->sortable(),

            DateTime::make('Installation Date', 'action_date')->sortable(),

            Text::make('Installation Notes', 'action_notes'),

            BelongsTo::make('User')->nullable(),
            Select::make('Action Type')
                ->options([
                    'installation' => 'Installation',
                    'uninstallation' => 'Uninstallation',
                ])->displayUsingLabels()->sortable(),
            Select::make('Status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                ])->displayUsingLabels()->sortable()->hideFromIndex(),

            Badge::make(__('Status'))->map([
                'pending' => 'warning',
                'in_progress' => 'info',
                'completed' => 'success',
                'failed' => 'danger',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
                'info' => 'refresh',
                'warning' => 'clock',
            ]),
        ];
    }

    /**
     * Get the filters available to the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the cards available to the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available to the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'installations';
    }
}