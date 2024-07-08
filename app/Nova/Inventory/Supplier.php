<?php

namespace App\Nova\Inventory;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Supplier extends Resource
{
    public static $model = \App\Models\Inventory\Supplier::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'document', 'email'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Contact')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Document')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make('Description')
                ->hideFromIndex(),
            Country::make('Country')->searchable()
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('City')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Postal Code')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:255'),
            Text::make('Phone')
                ->sortable()
                ->rules('required', 'max:255')
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createSupplier');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateSupplier', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteSupplier', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnySupplier');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewSupplier', $this->resource);
    }
}
