<?php

namespace App\Nova\Actions\Service;

use App\Events\FinalizeInvoice;
use App\Services\Billing\CustomerBillingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
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
        $invoice = false;
        $serviceBuildInvoice = new CustomerBillingService();
        foreach ($models as $model) {
            $customer = $model->customer;

            if (!$customer) {
                continue;
            }

            $invoice = $serviceBuildInvoice->generateForPeriod($customer, now());

            if ($invoice) {
                if (empty($invoice->service_id)) {
                    $invoice->service()->associate($model);
                    if ($model->router_id && empty($invoice->router_id)) {
                        $invoice->router_id = $model->router_id;
                    }
                    $invoice->save();
                }

                event(new FinalizeInvoice($invoice));
            }
        }
        if ($models->count() > 1) {
            return ActionResponse::visit('/resources/invoices');
        } else {
            return ActionResponse::visit('/resources/invoices/' . $invoice->id);
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
