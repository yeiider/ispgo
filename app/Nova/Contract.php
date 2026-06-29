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
                ->help(__('contract.signed_help')),

            Select::make(__('contract.status'), 'status')->options([
                'draft' => 'Draft',
                'sent' => 'Sent',
                'signed' => 'Signed',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ])->displayUsingLabels()->sortable(),

            Text::make(__('Contract PDF'), function () {
                return $this->contract_pdf_path 
                    ? '<a href="'.$this->contract_pdf_url.'" target="_blank" class="no-underline dim text-primary font-bold">Download PDF</a>' 
                    : 'Not uploaded';
            })->asHtml()->onlyOnDetail(),

            Text::make(__('Cédula (ID Document)'), function () {
                return $this->cedula_path 
                    ? '<a href="'.$this->cedula_url.'" target="_blank" class="no-underline dim text-primary font-bold">View Cédula</a>' 
                    : 'Not uploaded';
            })->asHtml()->onlyOnDetail(),

            Text::make(__('Utility Bill'), function () {
                return $this->utility_bill_path 
                    ? '<a href="'.$this->utility_bill_url.'" target="_blank" class="no-underline dim text-primary font-bold">View Utility Bill</a>' 
                    : 'Not uploaded';
            })->asHtml()->onlyOnDetail(),
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
                    if (empty($contract->contract_pdf_path)) {
                        return Action::danger("No se encontró el PDF firmado para el contrato #{$contract->id}");
                    }
                    return Action::redirect($contract->contract_pdf_url);
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
