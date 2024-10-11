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
            Boolean::make(__('Enable for Sales?.'), 'status')
                ->sortable()
                ->rules('required')->default(false),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('SKU')
                ->sortable()
                ->rules('required', 'max:255'),
            Number::make(__('Qty'), 'qty')->default(0)
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Brand'), 'brand')
                ->sortable()
                ->rules('nullable', 'max:255'),
            Image::make(__('Image'), 'image')->path('inventory/img'),
            Currency::make(__('Price'), 'price')
                ->sortable()
                ->rules('required', 'numeric'),
            Currency::make(__('Special Price'), 'special_price')
                ->sortable()
                ->rules('nullable', 'numeric'),
            Currency::make(__('Cost Price'), 'cost_price')
                ->sortable()
                ->rules('required', 'numeric'),
            Markdown::make(__('Description'), 'description')
                ->hideFromIndex(),
            Text::make(__('Reference'), 'reference')
                ->sortable()
                ->rules('nullable', 'max:255'),
            Number::make(__('Taxes'), 'taxes')
                ->sortable(),

            Text::make(__('URL Key'),'url_key')
                ->sortable(),
            BelongsTo::make(__('Warehouse'),'warehouse', Warehouse::class)
                ->sortable(),
            BelongsTo::make(__('Category'),'category', Category::class)
                ->sortable(),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Products');
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
