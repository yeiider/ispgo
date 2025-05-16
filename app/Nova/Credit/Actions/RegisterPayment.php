<?php

namespace App\Nova\Credit\Actions;

use App\Events\PaymentReceived;
use App\Models\Credit\CreditPayment;
use App\Services\Credit\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegisterPayment extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger(__('credit.select_one_account'));
        }

        $creditAccount = $models->first();
        $paymentService = app(PaymentService::class);

        try {
            // Create the payment
            $payment = new CreditPayment([
                'paid_at' => $fields->paid_at,
                'amount' => $fields->amount,
                'method' => $fields->method,
                'reference' => $fields->reference,
                'notes' => $fields->notes,
            ]);

            $payment->creditAccount()->associate($creditAccount);
            $payment->save();

            // Apply the payment to installments
            $paymentService->apply($creditAccount, $payment);

            // Dispatch event
            event(new PaymentReceived($creditAccount, $payment));

            return Action::message(__('credit.payment_registered', ['amount' => number_format($fields->amount, 2)]));
        } catch (\Exception $e) {
            return Action::danger(__('credit.error_registering_payment', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Currency::make(__('credit.amount'))
                ->required()
                ->min(0.01)
                ->step(0.01),

            DateTime::make(__('credit.paid_at'), 'paid_at')
                ->required()
                ->default(now()),

            Select::make(__('credit.method'))
                ->options([
                    'cash' => __('credit.cash'),
                    'bank_transfer' => __('credit.bank_transfer'),
                    'credit_card' => __('credit.credit_card'),
                    'debit_card' => __('credit.debit_card'),
                    'check' => __('credit.check'),
                    'other' => __('credit.other'),
                ])
                ->required(),

            Text::make(__('credit.reference'))
                ->help(__('credit.payment_reference'))
                ->nullable(),

            Textarea::make(__('credit.notes'))
                ->nullable(),
        ];
    }
}
