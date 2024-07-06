<?php

namespace Ispgo\SettingsManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class SettingsManager extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes();
        Nova::script('settings-manager', __DIR__.'/../dist/js/tool.js');
        Nova::style('settings-manager', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Settings Manager')
            ->path('/settings-manager')
            ->icon('cog');
    }
    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {

        Route::middleware(['nova', 'api'])
            ->prefix('/settings-manager')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Determine if the tool is authorized to be used.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize($request)
    {
        return $request->user() && $request->user()->can('Setting');
    }
}
