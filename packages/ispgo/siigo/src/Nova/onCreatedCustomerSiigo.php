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
                $identification = SiigoHelper::getCustomerIdentification($model);
                if (empty($identification)) {
                    return Action::danger(__(":customer no tiene identificación válida", ["customer" => $model->first_name . " " . $model->last_name]));
                }

                $payload = SiigoHelper::buildPayload($model);
                $siigo = app(\Ispgo\Siigo\SiigoClient::class);

                // Check if customer already exists in Siigo
                $existingCustomer = null;
                try {
                    $searchResponse = $siigo->getCustomer($identification);
                    $searchBody = json_decode((string) $searchResponse->getBody(), true);
                    $results = $searchBody['results'] ?? [];
                    if (!empty($results)) {
                        $existingCustomer = $results[0];
                    }
                } catch (\Exception $searchEx) {
                    // Ignore search errors, continue to create/update
                }

                if ($existingCustomer && !empty($existingCustomer['id'])) {
                    $siigo->updateCustomer($existingCustomer['id'], $payload);
                } else {
                    $siigo->createCustomer($payload);
                }
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
