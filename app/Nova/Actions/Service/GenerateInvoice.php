<?php

namespace App\Nova\Actions\Invoice\Service;

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
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $invoice = false;
        foreach ($models as $model) {
            $invoice = $model->generateInvoice($fields->notes);
        }
        if ($models->count() > 1) {
            return ActionResponse::visit('/resources/invoices');
        } else {
            return ActionResponse::visit('/resources/invoices/'.$invoice->id);
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
}
