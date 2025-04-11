<?php

namespace App\Nova\Actions\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegisterPaymentByPromise extends Action
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
        foreach ($models as $model) {
            if ($model->status !== "fulfilled") {
                $model->invoice->applyPayment();
                $model->status = 'fulfilled';
                $model->save();
            } else {
                return ActionResponse::danger(__('It is only allowed to record payments for promises that have not been paid'));

            }

        }
        return Action::message('Payment generated successfully!');

    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }

    public function name(): \Illuminate\Foundation\Application|\Stringable|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('invoice.register_payment_by_promise');
    }
}
