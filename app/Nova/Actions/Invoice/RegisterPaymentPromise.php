<?php

namespace App\Nova\Actions\Invoice;

use App\Settings\InvoiceProviderConfig;
use Illuminate\Bus\Queueable;
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
                $configValue = InvoiceProviderConfig::enableServiceByPaymentPromise();
                $shouldActivate = true;

                if ($configValue !== null) {
                    $configBoolean = filter_var($configValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    $shouldActivate = $configBoolean !== false;
                }

                if ($shouldActivate) {
                    $model->loadMissing('service');
                    optional($model->service)->activate();
                }
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

    public function name(): \Illuminate\Foundation\Application|\Stringable|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('invoice.actions.register_payment_promise');
    }
}
