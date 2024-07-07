<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegisterPaymentPromise extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $promise = false;
        foreach ($models as $model) {
            if ($model->paymentPromises->count()) {
                return ActionResponse::danger(__('There is already a promise to pay for this invoice :id', ['id' => $model->id]));
            }
            $promise = $model->createPromisePayment($fields->date_to_make_payment, $fields->notes);
            if ($promise) {
                $model->status = 'paid'; // Asumiendo que el campo es 'status' en lugar de 'paid'
                $model->save();
            }
        }
        if ($models->count() > 1) {
            return ActionResponse::visit('/resources/payment-promises');
        } else {
            return ActionResponse::visit('/resources/payment-promises/' . $promise->id);
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
            Date::make(__("Date to make payment"))->rules('required'),
            Textarea::make(__("Notes")),
        ];
    }
}
