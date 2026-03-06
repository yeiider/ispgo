<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use App\Models\User;
use App\Settings\GeneralProviderConfig;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterPaymentPromiseMutation
{
    public function resolve($_, array $args)
    {
        try {
            $invoice = Invoice::find($args['invoice_id']);

            if (!$invoice) {
                return [
                    'success' => false,
                    'message' => __('Invoice not found.'),
                    'payment_promise' => null,
                ];
            }

            if ($invoice->paymentPromises->count()) {
                return [
                    'success' => false,
                    'message' => __('There is already a promise to pay for this invoice :id', ['id' => $invoice->id]),
                    'payment_promise' => null,
                ];
            }

            // Si no hay usuario autenticado, establecer el usuario por defecto
            if (!Auth::check()) {
                $defaultUserId = GeneralProviderConfig::getDefaultUser();
                if ($defaultUserId) {
                    $defaultUser = User::find($defaultUserId);
                    if ($defaultUser) {
                        Auth::setUser($defaultUser);
                    }
                }
            }

            $promise = $invoice->createPromisePayment($args['promise_date'], $args['notes'] ?? null);

            if ($promise) {
                $configValue = InvoiceProviderConfig::enableServiceByPaymentPromise();
                $shouldActivate = true;

                if ($configValue !== null) {
                    $configBoolean = filter_var($configValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    $shouldActivate = $configBoolean !== false;
                }

                if ($shouldActivate) {
                    $invoice->loadMissing('service');
                    optional($invoice->service)->activate();
                }
            }

            return [
                'success' => true,
                'message' => __('Payment promise registered successfully!'),
                'payment_promise' => $promise,
            ];

        } catch (\Exception $e) {
            Log::error('Error in RegisterPaymentPromiseMutation', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error registering payment promise: :message', ['message' => $e->getMessage()]),
                'payment_promise' => null,
            ];
        }
    }
}
