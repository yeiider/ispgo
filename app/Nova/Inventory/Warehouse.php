<?php

namespace App\Nova\Inventory;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Warehouse extends Resource
{
    public static $model = \App\Models\Inventory\Warehouse::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'code', 'address'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Address')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Code')
                ->sortable()
                ->rules('required', 'max:255'),

        ];
    }


    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createWarehouse');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateWarehouse', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteWarehouse', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyWarehouse');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewWarehouse', $this->resource);
    }
}
