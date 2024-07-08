<?php

namespace App\Nova\Inventory;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    public static $model = \App\Models\Inventory\Category::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'url_key'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Description')
                ->hideFromIndex(),
            Text::make('URL Key')
                ->sortable()
                ->rules('required', 'max:255'),
        ];
    }


    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createCategory');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateCategory', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteCategory', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyCategory');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewCategory', $this->resource);
    }
}
