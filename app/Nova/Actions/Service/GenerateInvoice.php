<?php

namespace App\Nova\Actions\Service;

use App\Events\FinalizeInvoice;
use App\Models\Customers\Customer;
use App\Models\Services\Service;
use App\Services\Billing\CustomerBillingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateInvoice extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     * @throws \Exception
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $invoice = null;
        $serviceBuildInvoice = new CustomerBillingService();
        $service = null;
        $lastError = null;

        foreach ($models as $model) {
            try {
                // Detectar si el modelo es un Customer o un Service
                if ($model instanceof Customer) {
                    $customer = $model;
                    $service = null;
                } elseif ($model instanceof Service) {
                    $customer = $model->customer;
                    $service = $model;
                } else {
                    continue;
                }

                if (!$customer) {
                    $lastError = __('El cliente no está asociado correctamente.');
                    continue;
                }
                try {
                    $invoice = $serviceBuildInvoice->generateForPeriod($customer, now());
                } catch (\Exception $exception) {
                    return ActionResponse::danger($exception->getMessage());
                }


                if ($invoice) {
                    // Si se generó desde un Service, asociar el servicio a la factura
                    if ($service && empty($invoice->service_id)) {
                        $invoice->service()->associate($service);
                        if ($service->router_id && empty($invoice->router_id)) {
                            $invoice->router_id = $service->router_id;
                        }
                        $invoice->save();
                    }

                    event(new FinalizeInvoice($invoice));
                } else {
                    // Si no se generó la factura, verificar por qué
                    $activeServices = $customer->activeServices()->count();
                    if ($activeServices === 0) {
                        $lastError = __('El cliente no tiene servicios activos. Verifique que el cliente tenga servicios con estado diferente a "free" o "inactive".');
                    } else {
                        $lastError = __('No se pudo generar la factura para el cliente.');
                    }
                }
            } catch (\Exception $e) {
                $lastError = __('Error al generar la factura: :message', ['message' => $e->getMessage()]);
                Log::error('Error en GenerateInvoice action', [
                    'model_id' => $model->id ?? null,
                    'model_type' => get_class($model),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                continue;
            }
        }

        if ($models->count() > 1) {
            if ($invoice) {
                return ActionResponse::visit('/resources/invoices');
            } else {
                return ActionResponse::danger($lastError ?? __('No se pudo generar ninguna factura.'));
            }
        } elseif ($invoice) {
            return ActionResponse::visit('/resources/invoices/' . $invoice->id);
        } else {
            return ActionResponse::danger($lastError ?? __('No se pudo generar la factura. Verifique que el cliente tenga servicios activos.'));
        }
    }


    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Notes')
                ->nullable(),
        ];
    }

    public function name()
    {
        return __('service.actions.generate_invoice');
    }
}
