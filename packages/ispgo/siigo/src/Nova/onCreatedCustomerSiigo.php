<?php

namespace Ispgo\Siigo\Nova;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Ispgo\Siigo\Helpers\SiigoHelper;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class onCreatedCustomerSiigo extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Enviar clientes a Siigo';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            try {
                // Verifica si se seleccionó un técnico
                if (!$model->taxDetails) {
                    return Action::danger('No tiene informacion de impuestos');
                }
                if (!$model->taxDetails->enable_billing) {
                    return Action::danger(__(":customer no tiene habilitado el pago por impuestos", ["customer" => $model->first_name . " " . $model->last_name]));
                }
                $payload = SiigoHelper::buildPayload($model);
                dd($payload);
            } catch (\Exception $e) {
                return Action::danger('Error: ' . $e->getMessage());
            }
        }
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
