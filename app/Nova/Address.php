<?php

namespace App\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class Address extends Resource
{
    public static $model = \App\Models\Address::class;

    public static $title = 'address';

    public static $search = [
        'id', 'address', 'city', 'state_province', 'postal_code', 'country'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Customer'), 'customer', Customer::class),
            Text::make(__('Address'), 'address')->sortable()->rules('required', 'max:100'),
            Text::make(__('City'), 'city')->sortable()->rules('required', 'max:100'),
            Text::make(__('State/Province'), 'state_province')->sortable()->rules('required', 'max:100'),
            Text::make(__('Postal Code'), 'postal_code')->sortable()->rules('required', 'max:20'),
            Country::make(__('Country'), 'country')->sortable()->rules('required', 'max:100'),
            Select::make(__('Address Type'), 'address_type')
                ->options([
                    'billing' => __('Billing'),
                    'shipping' => __('Shipping'),
                ])
                ->sortable()->rules('required'),
            Number::make(__('Latitude'), 'latitude')->nullable()->step(0.0000001),
            Number::make(__('Longitude'), 'longitude')->nullable()->step(0.0000001),
        ];
    }
}