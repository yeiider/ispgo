<?php

namespace App\Nova\Filters;

use App\Models\Router;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class RouterFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Router (Zone)';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('router_id', $value);
    }

    /**
     * Get the options for the filter.
     *
     * @param Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return Router::where('status', 'enabled')
            ->pluck('id', 'name')
            ->toArray();
    }
}
