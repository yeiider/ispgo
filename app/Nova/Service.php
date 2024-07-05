<?php

namespace App\Nova;

use App\Nova\Actions\GenerateInvoice;
use App\Nova\Actions\UpdateCustomerStatus;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource;

class Service extends Resource
{
    public static $model = \App\Models\Service::class;

    public static $title = 'service_ip';

    public static $search = [
        'id', 'service_ip', 'username_router', 'service_status'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            new Panel('Customer & Router Details', $this->customerRouterFields()),

            new Panel('Service Details', $this->serviceDetailsFields()),

            new Panel('Billing & Contract Information', $this->billingContractFields()),

            new Panel('Technical Information', $this->technicalInformationFields()),
        ];
    }

    protected function customerRouterFields()
    {
        return [
            BelongsTo::make('Customer', 'customer', \App\Nova\Customer::class)
                ->searchable(),
            BelongsTo::make('Router', 'router', \App\Nova\Router::class),
        ];
    }

    protected function serviceDetailsFields()
    {
        return [
            BelongsTo::make('Internet Plan', 'internetPlan', \App\Nova\InternetPlan::class),
            Text::make('Service IP', 'service_ip')->sortable(),
            Text::make('Username Router', 'username_router'),
            Text::make('Password Router', 'password_router')->hideFromIndex(),
            Select::make('Service Status', 'service_status')->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
                'suspended' => 'Suspended',
                'pending' => 'Pending'
            ])->displayUsingLabels(),
            Badge::make(__('Status'), 'service_status')->map([
                'active' => 'success',
                'inactive' => 'danger',
                'suspended' => 'info',
                'pending' => 'warning',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
                'info' => 'status-offline',
                'warning' => 'clock',
            ]),
            Date::make('Activation Date', 'activation_date'),
            Date::make('Deactivation Date', 'deactivation_date'),
        ];
    }

    protected function billingContractFields()
    {
        return [
            Text::make('Support Contact', 'support_contact'),
            Text::make('Service Location', 'service_location')->hideFromIndex(),
            Text::make('Billing Cycle', 'billing_cycle'),
            Number::make('Monthly Fee', 'monthly_fee')->step(0.01),
            Number::make('Overage Fee', 'overage_fee')->step(0.01),
            Textarea::make('Service Contract', 'service_contract'),
        ];
    }

    protected function technicalInformationFields()
    {
        return [
            Number::make('Bandwidth', 'bandwidth')->hideFromIndex(),
            Text::make('MAC Address', 'mac_address')->hideFromIndex(),
            Date::make('Installation Date', 'installation_date'),
            Textarea::make('Service Notes', 'service_notes'),
            Boolean::make('Static IP', 'static_ip')->hideFromIndex(),
            Number::make('Data Limit', 'data_limit')->hideFromIndex(),
            Date::make('Last Maintenance', 'last_maintenance')->hideFromIndex(),
            Select::make('Service Priority', 'service_priority')->options([
                'normal' => 'Normal',
                'high' => 'High',
                'critical' => 'Critical'
            ])->displayUsingLabels(),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            new GenerateInvoice(),
        ];
    }
}
