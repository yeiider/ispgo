<?php

namespace App\Nova\Inventory;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
{
    public static $model = \App\Models\Inventory\Product::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'sku', 'url_key'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Boolean::make('Status')
                ->sortable()
                ->rules('required')->default(true),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('SKU')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Brand')
                ->sortable()
                ->rules('nullable', 'max:255'),
            Image::make('Image')
                ->sortable()
                ->rules('nullable', 'max:255'),
            Currency::make('Price')
                ->sortable()
                ->rules('required', 'numeric'),
            Currency::make('Special Price')
                ->sortable()
                ->rules('nullable', 'numeric'),
            Currency::make('Cost Price')
                ->sortable()
                ->rules('required', 'numeric'),
            Markdown::make('Description')
                ->hideFromIndex(),
            Text::make('Reference')
                ->sortable()
                ->rules('nullable', 'max:255'),
            Number::make('Taxes')
                ->sortable()
                ->rules('required', 'numeric'),

            Text::make('URL Key')
                ->sortable()
                ->rules('required', 'max:255'),
            BelongsTo::make('Warehouse')
                ->sortable(),
            BelongsTo::make('Category')
                ->sortable(),

        ];
    }


    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createProduct');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateProduct', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteProduct', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyProduct');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewProduct', $this->resource);
    }
}
