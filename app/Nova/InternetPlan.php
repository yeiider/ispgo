<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Panel;

class InternetPlan extends Resource
{
    public static $model = \App\Models\InternetPlan::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'description'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            new Panel(__('Basic Information'), $this->basicInformationFields()),
            new Panel(__('Plan Details'), $this->planDetailsFields()),
            new Panel(__('Customer Feedback'), $this->customerFeedbackFields()),
            new Panel(__('Technical Details'), $this->technicalDetailsFields()),
        ];
    }

    protected function basicInformationFields()
    {
        return [
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('Monthly Price'), 'monthly_price')
                ->sortable()
                ->rules('required'),
            Number::make(__('Download Speed'), 'download_speed')
                ->sortable()
                ->rules('required')->help(__('specify speed in MG')),
            Number::make(__('Upload Speed'), 'upload_speed')
                ->sortable()
                ->rules('required'),
            Boolean::make(__('Unlimited Data'), 'unlimited_data'),
            Select::make(__('Connection Type'), 'connection_type')
                ->options([
                    'Fiber Optic' => __('Fiber Optic'),
                    'ADSL' => __('ADSL'),
                    'Satellite' => __('Satellite'),
                ])
                ->rules('required'),
            Select::make(__('Status'), 'status')
                ->options([
                    'Active' => __('Active'),
                    'Inactive' => __('Inactive'),
                    'Pending' => __('Pending'),
                ])
                ->rules('required'),
        ];
    }

    protected function planDetailsFields()
    {
        return [
            Textarea::make(__('Description'), 'description')
                ->alwaysShow(),
            Number::make(__('Data Limit'), 'data_limit')
                ->sortable()
                ->nullable(),
            Text::make(__('Contract Period'), 'contract_period')
                ->nullable(),
            Textarea::make(__('Promotions'), 'promotions')
                ->nullable()
                ->alwaysShow(),
            Textarea::make(__('Extras Included'), 'extras_included')
                ->nullable()
                ->alwaysShow(),
            Textarea::make(__('Geographic Availability'), 'geographic_availability')
                ->nullable()
                ->alwaysShow(),
            Date::make(__('Promotion Start Date'), 'promotion_start_date')
                ->nullable(),
            Date::make(__('Promotion End Date'), 'promotion_end_date')
                ->nullable(),
            Image::make(__('Plan Image'), 'plan_image')
                ->nullable(),
        ];
    }

    protected function customerFeedbackFields()
    {
        return [
            Number::make(__('Customer Rating'), 'customer_rating')
                ->nullable(),
            Textarea::make(__('Customer Reviews'), 'customer_reviews')
                ->nullable()
                ->alwaysShow(),
        ];
    }

    protected function technicalDetailsFields()
    {
        return [
            Textarea::make(__('Service Compatibility'), 'service_compatibility')
                ->nullable()
                ->alwaysShow(),
            Text::make(__('Network Priority'), 'network_priority')
                ->nullable(),
            Textarea::make(__('Technical Support'), 'technical_support')
                ->nullable()
                ->alwaysShow(),
            Textarea::make(__('Additional Benefits'), 'additional_benefits')
                ->nullable()
                ->alwaysShow(),
        ];
    }
}
