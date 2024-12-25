<?php

namespace App\Nova;

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

            Select::make(__('contract.signed'), 'is_singned')
                ->options([
                    'pending' => __('contract.pending_signature'),
                    'signed' => __('contract.signed_signature'),
                ])
                ->displayUsingLabels()
                ->sortable()
                ->rules('required', 'in:pending,signed')
                ->help(__('contract.signed_help')),

            Boolean::make(__('contract.signed_status'), function () {
                return $this->is_singned === 'signed';
            })
                ->onlyOnIndex()
                ->sortable(),
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
            // Acción para enviar el contrato al cliente
            new \App\Nova\Actions\SendContractToCustomerAction(),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('contract.contract');
    }
}
