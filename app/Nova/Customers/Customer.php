<?php

namespace App\Nova\Customers;

use App\Models\Customers\DocumentType;
use App\Nova\Actions\UpdateCustomerStatus;
use App\Nova\Filters\CustomerStatus;
use App\Nova\Metrics\NewCustomers;
use App\Nova\Resource;
use App\Nova\Service;
use Illuminate\Http\Request;
use Ispgo\Ckeditor\Ckeditor;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Customer extends Resource
{
    public static $model = \App\Models\Customers\Customer::class;

    public static $title = 'full_name';

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
            Badge::make(__('Status'), 'customer_status')->map([
                'active' => 'success',
                'inactive' => 'danger',
                'suspended' => 'warning',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle'
            ]),
            Select::make(__('Customer Status'), 'customer_status')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive')
                ])->hideFromIndex()
                ->sortable()->rules('required'),
            Textarea::make(__('Additional Notes'), 'additional_notes')->nullable(),
            HasOne::make(__('Tax Details'), 'taxDetails', TaxDetail::class),
            HasMany::make(__('Addresses'), 'addresses', Address::class),
            HasMany::make(__('Services'), 'services', Service::class),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            new UpdateCustomerStatus,
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createCustomer');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateCustomer', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteCustomer', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyCustomer');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewCustomer', $this->resource);
    }

    public function filters(Request $request)
    {
        return [
            new CustomerStatus,
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            new NewCustomers
        ];
    }
}
