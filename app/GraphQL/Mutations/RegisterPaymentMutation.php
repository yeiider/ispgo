<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use App\Models\User;
use App\Settings\GeneralProviderConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterPaymentMutation
{
    public function resolve($_, array $args)
    {
        try {
            $invoice = Invoice::find($args['invoice_id']);

            if (!$invoice) {
                return [
                    'success' => false,
                    'message' => __('Invoice not found.'),
                ];
            }

            if ($invoice->status === Invoice::STATUS_PAID) {
                return [
                    'success' => false,
                    'message' => __('This invoice has already been paid!'),
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

            // Verificar si el usuario tiene una caja diaria asignada y abierta antes de permitir registrar pagos
            if (Auth::check()) {
                $user = Auth::user();
                $hasOpenRegister = \App\Models\Finance\CashRegister::where('user_id', $user->id)
                    ->where('status', \App\Models\Finance\CashRegister::STATUS_OPEN)
                    ->exists();

                if (!$hasOpenRegister) {
                    return [
                        'success' => false,
                        'message' => __('No puedes registrar pagos. Debes tener una caja diaria asignada y abierta en el sistema.'),
                    ];
                }
            }

            $paymentMethod = $args['payment_method'];
            $notes = $args['notes'] ?? null;
            $paymentRegisteredById = $args['payment_registered_by'] ?? null;

            $additional = [];
            if (!empty($args['transfer_reference'])) {
                $additional['transfer_reference'] = $args['transfer_reference'];
            }

            $invoice->applyPayment(null, $paymentMethod, $additional, $notes, null, false, $paymentRegisteredById);

            return [
                'success' => true,
                'message' => __('Payment generated successfully!'),
            ];

        } catch (\Exception $e) {
            Log::error('Error in RegisterPaymentMutation', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error registering payment: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
