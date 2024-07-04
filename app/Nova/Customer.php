<?php

namespace App\Nova;

use App\Models\DocumentType;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;

class Customer extends Resource
{
    public static $model = \App\Models\Customer::class;

    public static $title = 'email_address';

    public static $search = [
        'id', 'first_name', 'last_name', 'email_address', 'phone_number'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('First Name'), 'first_name')->sortable()->rules('required', 'max:100'),
            Text::make(__('Last Name'), 'last_name')->sortable()->rules('required', 'max:100'),
            Date::make(__('Date of Birth'), 'date_of_birth')->nullable(),
            Text::make(__('Phone Number'), 'phone_number')->nullable(),
            Text::make(__('Email Address'), 'email_address')->sortable()->rules('required', 'email', 'max:100'),
            Select::make(__('Document Type'), 'document_type')
                ->options(DocumentType::pluck('name', 'code')->toArray())
                ->sortable()
                ->rules('required', 'max:20'),
            Text::make(__('Identity Document'), 'identity_document')->rules('required', 'max:100'),
            Badge::make(__('Status'), 'status')->map([
                'active' => 'success',
                'inactive' => 'danger',
                'suspended' => 'info',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
                'suspended' => 'status-offline',
            ]),
            Select::make(__('Customer Status'), 'customer_status')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                    'suspended' => __('Suspended'),
                ])
                ->sortable()->rules('required'),
            Textarea::make(__('Additional Notes'), 'additional_notes')->nullable(),
            HasMany::make(__('Addresses'), 'addresses', Address::class),
            HasOne::make(__('Tax Details'), 'taxDetails', TaxDetail::class),
        ];
    }
}
