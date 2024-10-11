<?php

namespace App\Nova\Inventory;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    public static $model = \App\Models\Inventory\Category::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'url_key'
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('attribute.name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make(__('attribute.description'), 'description')
                ->hideFromIndex(),
            Text::make(__('attribute.url_key'), 'url_key')
                ->sortable()
                ->rules('required', 'max:255'),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('attribute.categories');
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
