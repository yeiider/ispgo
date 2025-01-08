<?php

namespace App\Nova;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class Contract extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Contract>
     */
    public static $model = \App\Models\Contract::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'customer_id',
        'service_id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('contract.customer'), 'customer', \App\Nova\Customers\Customer::class)
                ->sortable()
                ->rules('required'),

            BelongsTo::make(__('contract.service'), 'service', \App\Nova\Service::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            Date::make(__('contract.start_date'), 'start_date')
                ->sortable()
                ->rules('required', 'date')
                ->help(__('contract.start_date_help')),

            Date::make(__('contract.end_date'), 'end_date')
                ->sortable()
                ->rules('required', 'date', 'after_or_equal:start_date')
                ->help(__('contract.end_date_help')),

            Date::make(__('contract.signed_date'), 'signed_at')->onlyOnIndex(),

            Boolean::make(__('contract.signed'), 'is_signed')
                ->rules('required')
                ->help(__('contract.signed_help')),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new \App\Nova\Actions\SendContractToCustomerAction(),
            Action::using(__('Ver PDF Contrato'), function (ActionFields $fields, Collection $models) {
                foreach ($models as $contract) {
                    $pdfPath = "public/contracts/contract_{$contract->id}_signed.pdf";
                    if (!Storage::exists($pdfPath)) {
                        return Action::danger("No se encontró el PDF para el contrato #{$contract->id}");
                    }
                    $pdfUrl = Storage::url($pdfPath);
                    return Action::redirect($pdfUrl);
                }

                return Action::danger('No se seleccionó ningún contrato.');
            })->showInline(),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('contract.contract');
    }
}
