<?php

namespace App\Nova;

use App\Models\FiscalRegime;
use App\Models\TaxIdentificationType;
use App\Models\TaxpayerType;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;

class TaxDetail extends Resource
{
    public static $model = \App\Models\TaxDetail::class;

    public static $title = 'tax_identification_number';

    public static $search = [
        'id', 'tax_identification_number', 'taxpayer_type', 'fiscal_regime'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Customer'), 'customer', Customer::class),
            Select::make(__('Fiscal Document'), 'tax_identification_type')
                ->options(TaxIdentificationType::pluck('name', 'code')->toArray())
                ->sortable()
                ->rules('required', 'max:5'),

            Text::make(__('Tax Identification Number'), 'tax_identification_number')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make(__('Taxpayer Type'), 'taxpayer_type')
                ->options(TaxpayerType::pluck('name', 'code')->toArray())
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make(__('Fiscal Regime'), 'fiscal_regime')
                ->options(FiscalRegime::pluck('name', 'code')->toArray())
                ->sortable()
                ->rules('required', 'max:255'),
        ];
    }
}