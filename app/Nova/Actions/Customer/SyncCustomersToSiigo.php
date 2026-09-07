<?php

namespace App\Nova\Actions\Customer;

use App\Models\Customers\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class SyncCustomersToSiigo extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $count = 0;
        foreach ($models as $customer) {
            /** @var Customer $customer */
            $scopeId = (int) ($customer->router_id ?? 0);

            if (!ConfigProviderSiigo::getEnabled($scopeId)) {
                return Action::danger(__('La integración con Siigo no está habilitada.'));
            }

            CreateSiigoCustomer::dispatch($customer, true)->onQueue('redis');
            $count++;
        }

        return Action::message(__('Se enviaron :count cliente(s) a sincronizar con Siigo exitosamente en segundo plano.', ['count' => $count]));
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }

    public function name(): string
    {
        return __('Sincronizar con Siigo');
    }
}
