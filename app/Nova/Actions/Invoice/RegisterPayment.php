<?php

namespace App\Nova\Actions\Invoice;

use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegisterPayment extends Action
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
        // Store evidence file once per action execution
        $evidencePath = null;
        if (!empty($fields->payment_evidence)) {
            $evidencePath = $fields->payment_evidence->store('payment_evidence', 'public');
        }

        // Prepare additional info (shared for all selected invoices)
        $additional = [];
        if (is_array($fields->additional_data)) {
            $additional = $fields->additional_data;
        }
        if ($evidencePath) {
            $additional['payment_evidence'] = [
                'disk' => 'public',
                'path' => $evidencePath,
                'url' => Storage::disk('public')->url($evidencePath),
            ];
        }

        $paymentMethod = $fields->payment_method ?: 'cash';
        $notes = $fields->notes ?: null;

        foreach ($models as $model) {
            if ($model->status === Invoice::STATUS_PAID) {
                return ActionResponse::danger('There are invoices that have already been paid!');
            }

            if ($evidencePath) {
                $model->payment_support = $evidencePath;
            }

            // applyPayment will save the model; setting payment_support before ensures it persists
            $model->applyPayment(null, $paymentMethod, $additional, $notes, null);
        }
        return Action::message('Payment generated successfully!');
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
            Select::make(__('invoice.payment_method'), 'payment_method')
                ->options([
                    'cash' => __('Cash'),
                    'transfer' => __('Transfer'),
                    'card' => __('Card'),
                    'online' => __('Online'),
                ])->displayUsingLabels(),

            Textarea::make(__('invoice.notes'), 'notes')->rows(3),

            File::make(__('Payment Evidence'), 'payment_evidence')
                ->help(__('Attach an image or file as proof of payment'))
                ->rules('file', 'max:5120')
                ->store(function () { /* We will handle storage manually in handle() */ }),

            KeyValue::make(__('Additional Data'), 'additional_data')
                ->help(__('Optional key-value data to store as additional_information'))
                ->rules('nullable')
                ->disableAddingRows(false)
                ->disableEditingKeys(false),
        ];
    }

    public function name()
    {
        return __('invoice.actions.register_payment');
    }
}
