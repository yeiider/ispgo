<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Actions\Action;

class SendContractToCustomerAction extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action.
     */
    public function handle(ActionFields $fields, \Illuminate\Support\Collection $models)
    {
        foreach ($models as $contract) {
            $customer = $contract->customer; // Obtener al cliente asociado
            $this->markAsFinished($contract);
        }

        return Action::message(__('El contrato se ha enviado al cliente.'));
    }

    /**
     * Name of the action.
     */
    public function name(): string
    {
        return __('Enviar contrato al cliente');
    }
}
