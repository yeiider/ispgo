<?php

namespace App\Nova;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Illuminate\Http\Request;

class Router extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Router>
     */
    public static $model = \App\Models\Router::class;

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
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make(__('router.name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('router.code'), 'code')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make(__('router.status'), 'status')
                ->options([
                    'enabled' => __('router.enabled'),
                    'disabled' => __('router.disabled'),
                ])
                ->displayUsingLabels()
                ->default('enabled')
                ->rules('required'),

            Badge::make(__('router.status'), 'status')
                ->map([
                    'enabled' => 'success',
                    'disabled' => 'danger',
                ])
                ->icons([
                    'success' => 'check-circle',
                    'danger' => 'x-circle',
                ])
                ->label(fn($value) => __('router.' . $value)),

            \Laravel\Nova\Fields\HasMany::make(__('Users'), 'users', User::class),
            \Laravel\Nova\Fields\HasMany::make(__('Customers'), 'customers', \App\Nova\Customers\Customer::class),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Routers');
    }

    public static function singularLabel(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Router');
    }


    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createRouter');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateRouter', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteRouter', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyRouter');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewRouter', $this->resource);
    }
}
