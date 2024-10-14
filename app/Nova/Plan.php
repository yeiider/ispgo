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

    public function fields(Request $request): array
    {
        $basicPanel = new Panel(__('plan.basic_information'), $this->basicInformationFields());
        $planPanel = new Panel(__('plan.plan_details'), $this->planDetailsFields());
        $feedbackPanel = new Panel(__('plan.customer_feedback'), $this->customerFeedbackFields());
        $technicalPanel = new Panel(__('plan.technical_details'), $this->technicalDetailsFields());

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

    protected function basicInformationFields(): array
    {
        return [
            Select::make(__('attribute.status'), 'status')
                ->options([
                    'active' => __('attribute.active'),
                    'inactive' => __('attribute.inactive')
                ])->displayUsingLabels()->default('active')
                ->hideFromDetail()
                ->rules('required')->hideFromIndex(),

            Badge::make(__('attribute.status'), 'status')->map([
                'active' => 'success',
                'inactive' => 'danger',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
            ])->label(function ($value) {
                return __('attribute.' . $value);
            }),

            Select::make(__('plan.modality_type'), 'modality_type')
                ->options([
                    'postpaid' => __('plan.postpaid'),
                    'prepaid' => __('plan.prepaid'),
                ])->displayUsingLabels()->default('postpaid')->rules('required'),

            Select::make(__('plan.plan_type'), 'plan_type')
                ->options([
                    'internet' => __('plan.internet'),
                    'television' => __('plan.television'),
                    'telephonic' => __('plan.telephonic'),
                ])->displayUsingLabels()->default('internet')->rules('required'),


            Text::make(__('plan.name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('plan.monthly_price'), 'monthly_price')
                ->sortable()
                ->rules('required'),
            Currency::make(__('plan.overage_fee'), 'overage_fee')
                ->sortable(),
            Number::make(__('plan.download_speed'), 'download_speed')
                ->sortable()
                ->default(0)
                ->rules('required')
                ->help(__('plan.download_speed_help')),
            Number::make(__('plan.upload_speed'), 'upload_speed')
                ->sortable()
                ->default(0)
                ->rules('required'),
            Boolean::make(__('plan.unlimited_data'), 'unlimited_data'),


        ];
    }


    protected function planDetailsFields(): array
    {
        return [
            Textarea::make(__('plan.description'), 'description')
                ->alwaysShow()->hideFromIndex(),
            Number::make(__('plan.data_limit'), 'data_limit')
                ->sortable()
                ->nullable(),
            Text::make(__('plan.contract_period'), 'contract_period')
                ->nullable()->hideFromIndex(),
            Textarea::make(__('plan.promotions'), 'promotions')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
            Textarea::make(__('plan.extras_included'), 'extras_included')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
            Textarea::make(__('plan.geographic_availability'), 'geographic_availability')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
            Date::make(__('plan.promotion_start_date'), 'promotion_start_date')
                ->nullable(),
            Date::make(__('plan.promotion_end_date'), 'promotion_end_date')
                ->nullable(),
            Image::make(__('plan.plan_image'), 'plan_image')
                ->nullable()->hideFromIndex(),
        ];
    }

    protected function customerFeedbackFields(): array
    {
        return [
            Number::make(__('plan.customer_rating'), 'customer_rating')
                ->nullable(),
            Textarea::make(__('plan.customer_reviews'), 'customer_reviews')
                ->nullable()
                ->alwaysShow()->hideFromIndex(),
        ];
    }

    protected function attributesSmartOlt(): array
    {
        return [
            Text::make(__('plan.profile_smart_olt'), 'profile_smart_olt')
                ->nullable(),
        ];
    }

    protected function technicalDetailsFields(): array
    {
        return [
            Textarea::make(__('plan.service_compatibility'), 'service_compatibility')
                ->nullable()
                ->alwaysShow(),
            Text::make(__('plan.network_priority'), 'network_priority')
                ->nullable(),
            Textarea::make(__('plan.technical_support'), 'technical_support')
                ->nullable()
                ->alwaysShow(),
            Textarea::make(__('plan.additional_benefits'), 'additional_benefits')
                ->nullable()
                ->alwaysShow(),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Plans');
    }

    public static function singularLabel(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Plan');
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request): array
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
