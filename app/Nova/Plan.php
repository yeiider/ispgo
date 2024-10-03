<?php

namespace App\Nova;

use App\Nova\Lenses\TelephonicPlanLens;
use App\Nova\Lenses\TelevisionPlanLens;
use Illuminate\Http\Request;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Plan extends Resource
{
    public static $model = \App\Models\Services\Plan::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'description'
    ];

    public function fields(Request $request)
    {
        $basicPanel = new Panel(__('Basic Information'), $this->basicInformationFields());
        $planPanel = new Panel(__('Plan Details'), $this->planDetailsFields());
        $feedbackPanel = new Panel(__('Customer Feedback'), $this->customerFeedbackFields());
        $technicalPanel = new Panel(__('Technical Details'), $this->technicalDetailsFields());

        $panels = [
            ID::make()->sortable(),
            $basicPanel,
            $planPanel,
            $feedbackPanel,
            $technicalPanel,
        ];

        if (ProviderSmartOlt::getEnabled()) {
            $panels[] = new Panel(__('Smart OLT'), $this->attributesSmartOlt());
        }

        return $panels;
    }

    protected function basicInformationFields()
    {
        return [
            Select::make(__('Status'))
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive')
                ])->displayUsingLabels()->default('active')
                ->rules('required')->hideFromIndex(),

            Badge::make(__('Status'))->map([
                'active' => 'success',
                'inactive' => 'danger',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
            ]),

            Select::make(__('Modality Type'))
                ->options([
                    'postpaid' => __('Postpaid'),
                    'prepaid' => __('Prepaid'),
                ])->displayUsingLabels()->default('postpaid')->rules('required'),

            Select::make(__('Plan Type'))
                ->options([
                    'internet' => __('Internet'),
                    'television' => __('Television'),
                    'telephonic' => __('Telephonic'),
                ])->displayUsingLabels()->default('internet')->rules('required'),


            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('Monthly Price'), 'monthly_price')
                ->sortable()
                ->rules('required'),
            Currency::make(__('Overage Price'), 'overage_fee')
                ->sortable(),
            Number::make(__('Download Speed'), 'download_speed')
                ->sortable()
                ->default(0)
                ->rules('required')->help(__('specify speed in MG')),
            Number::make(__('Upload Speed'), 'upload_speed')
                ->sortable()
                ->default(0)
                ->rules('required'),
            Boolean::make(__('Unlimited Data'), 'unlimited_data'),


        ];
    }


    protected function planDetailsFields()
    {
        return [
            Textarea::make(__('Description'), 'description')
                ->alwaysShow()->hideFromIndex(),
            Number::make(__('Data Limit'), 'data_limit')
                ->sortable()
                ->nullable(),
            Text::make(__('Contract Period'), 'contract_period')
                ->nullable()->hideFromIndex(),
            Textarea::make(__('Promotions'), 'promotions')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
            Textarea::make(__('Extras Included'), 'extras_included')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
            Textarea::make(__('Geographic Availability'), 'geographic_availability')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
            Date::make(__('Promotion Start Date'), 'promotion_start_date')
                ->nullable(),
            Date::make(__('Promotion End Date'), 'promotion_end_date')
                ->nullable(),
            Image::make(__('Plan Image'), 'plan_image')
                ->nullable()->hideFromIndex(),
        ];
    }

    protected function customerFeedbackFields()
    {
        return [
            Number::make(__('Customer Rating'), 'customer_rating')
                ->nullable(),
            Textarea::make(__('Customer Reviews'), 'customer_reviews')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
        ];
    }

    protected function attributesSmartOlt()
    {
        return [
            Text::make(__('Profile'), 'profile_smart_olt')
                ->nullable(),
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

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [
            new TelevisionPlanLens(),
            new TelephonicPlanLens(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createPlan');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updatePlan', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deletePlan', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyPlan');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewPlan', $this->resource);
    }
}
