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
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegisterPayment extends Action
{
    use InteractsWithQueue, Queueable;

    public $withoutActionEvents = true;

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
        
        // Determine payment amount based on full_payment checkbox
        $isFullPayment = $fields->full_payment ?? true;
        $customAmount = $fields->custom_amount ?? null;

        foreach ($models as $model) {
            if ($model->status === Invoice::STATUS_PAID) {
                return ActionResponse::danger('There are invoices that have already been paid!');
            }

            if ($evidencePath) {
                $model->payment_support = $evidencePath;
            }

            // Determine the amount to pay
            if ($isFullPayment) {
                // Full payment: use null to let applyPayment calculate it
                $amountToPay = null;
                $createRecord = false; // Don't create payment record for full payments
            } else {
                // Partial payment: use custom amount
                if (!$customAmount || $customAmount <= 0) {
                    return ActionResponse::danger('Por favor ingrese un monto válido para el pago parcial.');
                }
                $amountToPay = $customAmount;
                $createRecord = true; // Create payment record ONLY for partial payments
            }

            // applyPayment will save the model; setting payment_support before ensures it persists
            $model->applyPayment($amountToPay, $paymentMethod, $additional, $notes, null, $createRecord);
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
            Boolean::make(__('Pago Completo'), 'full_payment')
                ->default(true)
                ->help(__('Desactiva esta opción para ingresar un monto parcial'))
                ->trueValue(true)
                ->falseValue(false),

            Currency::make(__('Monto del Pago'), 'custom_amount')
                ->step(0.01)
                ->help(__('Ingrese el monto a pagar'))
                ->dependsOn('full_payment', function (Currency $field, NovaRequest $request, $formData) {
                    if ($formData['full_payment'] === false) {
                        $field->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $field->hide();
                    }
                }),

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
