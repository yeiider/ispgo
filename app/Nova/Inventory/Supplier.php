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

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Contact'), 'contact')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Document'), 'document')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make(__('Description'), 'description')
                ->hideFromIndex(),
            Country::make(__('Country'), 'country')->searchable()
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('City'), 'city')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Postal Code'), 'postal_code')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Email'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:255'),
            Text::make(__('Phone'), 'phone')
                ->sortable()
                ->rules('required', 'max:255')
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Suppliers');
    }

    public static function singularLabel(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Supplier');
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
