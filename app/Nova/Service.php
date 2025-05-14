<?php

namespace App\Nova;


use App\Nova\Actions\SendWhatsAppMessage;
use App\Nova\Actions\Service\ActivateService;
use App\Nova\Actions\Service\CreateActionsServiceInstall;
use App\Nova\Actions\Service\CreateActionsServiceUninstall;
use App\Nova\Actions\Service\GenerateInvoice;
use App\Nova\Actions\Service\SuspendService;
use App\Nova\Customers\Address;
use App\Nova\Filters\ServiceStatus;
use App\Nova\Filters\ServiceType;
use App\Nova\Lenses\TelephonicServiceLens;
use App\Nova\Lenses\TelevisionServiceLens;
use Illuminate\Http\Request;
use Ispgo\Mikrotik\Nova\Actions\MikrotikAction;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Smartolt\Nova\Actions\LoadPlanSmartOlt;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource;
use Laravel\Nova\Tabs\Tab;

class Service extends Resource
{
    public static $model = \App\Models\Services\Service::class;

    public static $title = 'full_service_name';

    public static $search = [
        'id', 'service_ip', 'username_router', 'service_status'
    ];

    public function fields(NovaRequest $request)
    {
        $panels = [
            ID::make(__('ID'), 'id')->sortable(),

            new Panel(__('service.customer_and_router_details'), $this->customerRouterFields()),

            new Panel(__('service.service_details'), $this->serviceDetailsFields()),

            new Panel(__('service.billing_and_contract_information'), $this->billingContractFields()),

            new Panel(__('service.technical_information'), $this->technicalInformationFields()),
        ];

        if (ProviderSmartOlt::getEnabled()) {
            $panels[] = new Panel(__('Smart OLT'), $this->attributesSmartOlt());
        }

        $rules = Tab::make(__('Relgas de promociones'), [
            HasMany::make(__('rules'), 'rules', ServiceRule::class),
        ]);

        $panels[] = $rules;

        return $panels;
    }

    protected function customerRouterFields()
    {
        return [
            BelongsTo::make(__('customer.customer'), 'customer', Customers\Customer::class)
                ->searchable(),
            BelongsTo::make(__('router.router'), 'router', \App\Nova\Router::class),
        ];
    }

    protected function serviceDetailsFields(): array
    {
        return [
            BelongsTo::make(__('Plan'), 'plan', \App\Nova\Plan::class),
            Text::make(__('service.service_ip'), 'service_ip')->sortable(),
            Text::make(__('service.username_router'), 'username_router'),
            Text::make(__('service.password_router'), 'password_router')->hideFromIndex(),
            Select::make(__('service.service_status'), 'service_status')->options([
                'active' => __('attribute.active'),
                'inactive' => __('attribute.inactive'),
                'suspended' => __('attribute.suspended'),
                'pending' => __('attribute.pending'),
                'free' => __('attribute.free')
            ])->displayUsingLabels()->hideFromIndex(),
            Badge::make(__('attribute.status'), 'service_status')->map([
                'active' => 'success',
                'inactive' => 'danger',
                'suspended' => 'info',
                'pending' => 'warning',
                'free' => 'success',
            ])->icons([
                'danger' => 'exclamation-circle',
                'success' => 'check-circle',
                'info' => 'status-offline',
                'warning' => 'clock',
            ])->label(function ($value) {
                return __('attribute.' . $value);
            }),
            Date::make(__('service.activation_date'), 'activation_date'),
            Date::make(__('service.deactivation_date'), 'deactivation_date'),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('service.services');
    }

    protected function billingContractFields(): array
    {
        return [
            Text::make(__('service.support_contact'), 'support_contact'),

            BelongsTo::make(__('service.address'), 'address', Address::class)->hideFromIndex()->searchable(),
            Text::make(__('service.billing_cycle'), 'billing_cycle'),
            Textarea::make(__('service.service_contract'), 'service_contract'),
        ];
    }

    protected function attributesSmartOlt(): array
    {
        return [
            Text::make(__('Onu SN'), 'sn'),
        ];
    }

    protected function technicalInformationFields(): array
    {
        return [
            Number::make(__('service.bandwidth'), 'bandwidth')->hideFromIndex(),

            Text::make(__('service.mac_address'), 'mac_address')->hideFromIndex(),
            Date::make(__('service.installation_date'), 'installation_date'),
            Textarea::make(__('service.service_notes'), 'service_notes'),
            Boolean::make(__('service.static_ip'), 'static_ip')->hideFromIndex(),
            Number::make(__('service.data_limit'), 'data_limit')->hideFromIndex(),
            Date::make(__('service.last_maintenance'), 'last_maintenance')->hideFromIndex(),
            Select::make(__('service.service_priority'), 'service_priority')->options([
                'normal' => __('attribute.normal'),
                'high' => __('attribute.high'),
                'critical' => __('attribute.critical')
            ])->displayUsingLabels()->default('normal'),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new ActivateService(),
            new SuspendService(),
            new GenerateInvoice(),
            new CreateActionsServiceInstall(),
            new CreateActionsServiceUninstall(),
            (new MikrotikAction())->canSee(function () {
                return MikrotikConfigProvider::getEnabled();
            }),
            (new LoadPlanSmartOlt())->canSee(function () {
                return ProviderSmartOlt::getEnabled();
            }),
            Action::downloadUrl('Exportar Servicioss', function () {
                return route('service.export');
            })->standalone(),
            new SendWhatsAppMessage(),
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
            new TelephonicServiceLens(),
            new TelevisionServiceLens()
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ServiceStatus(),
            new ServiceType()
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createService');
    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateService', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteService', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyService');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewService', $this->resource);
    }


}
