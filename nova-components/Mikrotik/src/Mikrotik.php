<?php

namespace Ispgo\Mikrotik;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class Mikrotik extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('mikrotik', __DIR__.'/../dist/js/tool.js');
        Nova::style('mikrotik', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Mikrotik')
            ->path('/mikrotik')
            ->icon('server');
    }

    /**
     * Determine if the tool is authorized to be used.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize($request)
    {
        //return $request->user() && $request->user()->can('Setting');
        return true;
    }
}
