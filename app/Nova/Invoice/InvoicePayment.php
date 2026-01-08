<?php

namespace App\Nova\Invoice;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoicePayment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice\InvoicePayment::class;

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
        'id', 'reference_number', 'notes'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('invoice.invoice'), 'invoice', Invoice::class)
                ->searchable()
                ->readonly(),

            BelongsTo::make(__('Usuario'), 'user', \App\Nova\User::class)
                ->readonly()
                ->hideFromIndex(),

            Currency::make(__('Monto'), 'amount')
                ->step(0.01)
                ->rules('required', 'numeric', 'min:0.01')
                ->sortable(),

            Date::make(__('Fecha de Pago'), 'payment_date')
                ->rules('required', 'date')
                ->sortable(),

            Select::make(__('Método de Pago'), 'payment_method')
                ->options([
                    'cash' => __('Efectivo'),
                    'transfer' => __('Transferencia'),
                    'card' => __('Tarjeta'),
                    'online' => __('En línea'),
                ])
                ->displayUsingLabels()
                ->sortable(),

            Text::make(__('Referencia'), 'reference_number')
                ->rules('nullable', 'string', 'max:255')
                ->hideFromIndex(),

            Textarea::make(__('Notas'), 'notes')
                ->rows(3)
                ->hideFromIndex(),

            Text::make(__('Comprobante'), 'payment_support')
                ->hideFromIndex(),

            KeyValue::make(__('Información Adicional'), 'additional_information')
                ->hideFromIndex()
                ->readonly(),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return __('Abonos a Facturas');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return __('Abono a Factura');
    }

    /**
     * Determine if the user can create the resource.
     *
     * @param Request $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('createInvoicePayment');
    }

    /**
     * Determine if the user can update the resource.
     *
     * @param Request $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateInvoicePayment', $this->resource);
    }

    /**
     * Determine if the user can delete the resource.
     *
     * @param Request $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteInvoicePayment', $this->resource);
    }

    /**
     * Determine if the user can view any resources.
     *
     * @param Request $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyInvoicePayment');
    }

    /**
     * Determine if the user can view the resource.
     *
     * @param Request $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewInvoicePayment', $this->resource);
    }
}
