<?php

namespace App\Nova;

use Illuminate\Validation\Rule;
use Laravel\Nova\Http\Requests\NovaRequest;
use Sereny\NovaPermissions\Nova\Role as BaseRole;

class Role extends BaseRole
{
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields($request)
    {
        $guardOptions = $this->guardOptions($request);
        $userResource = $this->userResource();

        // Convert guardOptions to array of values for Rule::in()
        // If it's a collection or array with keys, extract the keys (guard names)
        $guardNames = is_object($guardOptions) 
            ? $guardOptions->keys()->toArray() 
            : (is_array($guardOptions) ? array_keys($guardOptions) : []);

        return [
            \Laravel\Nova\Fields\ID::make(__('ID'), 'id')
                ->rules('required')
                ->canSee(function ($request) {
                    return $this->fieldAvailable('id');
                }),

            \Laravel\Nova\Fields\Text::make(__('Name'), 'name')
                ->rules(['required', 'string', 'max:255'])
                ->creationRules('unique:' . config('permission.table_names.roles'))
                ->updateRules('unique:' . config('permission.table_names.roles') . ',name,{{resourceId}}'),

            \Laravel\Nova\Fields\Select::make(__('Guard Name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardNames)])
                ->canSee(function ($request) {
                    return $this->fieldAvailable('guard_name');
                })
                ->default($this->defaultGuard($guardOptions)),

            \Sereny\NovaPermissions\Fields\Checkboxes::make(__('Permissions'), 'permissions')
                ->options($this->loadPermissions()->map(function ($permission, $key) {
                    return [
                        'group'  => __(ucfirst($permission->group)),
                        'option' => $permission->name,
                        'label'  => __($permission->name),
                    ];
                })
                    ->groupBy('group')
                    ->toArray()),

            \Laravel\Nova\Fields\Text::make(__('Users'), function () {
                /**
                 * We eager load count for the users relationship in the index query.
                 * @see self::indexQuery()
                 */
                return isset($this->users_count) ? $this->users_count : $this->users()->count();
            })->exceptOnForms(),

            \Laravel\Nova\Fields\MorphToMany::make($userResource::label(), 'users', $userResource)
                ->searchable()
                ->canSee(function ($request) {
                    return $this->fieldAvailable('users');
                }),
        ];
    }
}

