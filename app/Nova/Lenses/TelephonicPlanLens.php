<?php

namespace App\Nova\Lenses;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;

class TelephonicPlanLens extends Lens
{

    /**
     * Get the name of the lens.
     *
     * @return string
     */

    public function name(): string
    {
        return __('panel.telephonic_plans');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->where("plan_type", "telephonic")
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(Nova::__('ID'), 'id')->sortable(), Badge::make(__('Status'))->map([
                'active' => 'success',
                'inactive' => 'danger',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
            ])->label(function ($value) {
                return __($value);
            }),
            Select::make(__('Modality Type'))
                ->options([
                    'postpaid' => __('Postpaid'),
                    'prepaid' => __('Prepaid'),
                ])->displayUsingLabels()->default('postpaid')->rules('required'),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('Monthly Price'), 'monthly_price')
                ->sortable()
                ->rules('required'),
        ];

    }

    /**
     * Get the cards available on the lens.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'telephonic-plan-lens';
    }
}
