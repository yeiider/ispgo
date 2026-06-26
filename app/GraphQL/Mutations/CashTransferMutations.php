<?php

namespace App\GraphQL\Mutations;

use App\Models\Finance\CashTransfer;
use Exception;
use Illuminate\Support\Facades\Log;

class CashTransferMutations
{
    /**
     * Create a new cash transfer (delivery to admin)
     */
    public function create($_, array $args)
    {
        try {
            $transfer = CashTransfer::create([
                'sender_cash_register_id' => $args['sender_cash_register_id'],
                'receiver_cash_register_id' => $args['receiver_cash_register_id'],
                'amount' => $args['amount'],
                'notes' => $args['notes'] ?? null,
                'status' => 'pending',
            ]);

            return [
                'success' => true,
                'message' => 'Entrega de dinero registrada correctamente. Pendiente de verificación por el administrador.',
                'cashTransfer' => $transfer
            ];
        } catch (Exception $e) {
            Log::error('Error creating cash transfer: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al registrar la entrega: ' . $e->getMessage(),
                'cashTransfer' => null
            ];
        }
    }

    /**
     * Accept a pending cash transfer
     */
    public function accept($_, array $args)
    {
        try {
            $transfer = CashTransfer::findOrFail($args['id']);
            
            if ($transfer->status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'Esta entrega ya fue procesada anteriormente.',
                    'cashTransfer' => null
                ];
            }

            $transfer->status = 'accepted';
            $transfer->save();

            return [
                'success' => true,
                'message' => 'Entrega aceptada exitosamente. El dinero ha sido ingresado a su caja.',
                'cashTransfer' => $transfer
            ];
        } catch (Exception $e) {
            Log::error('Error accepting cash transfer: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al aceptar la entrega: ' . $e->getMessage(),
                'cashTransfer' => null
            ];
        }
    }

    /**
     * Reject a pending cash transfer
     */
    public function reject($_, array $args)
    {
        try {
            $transfer = CashTransfer::findOrFail($args['id']);
            
            if ($transfer->status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'Esta entrega ya fue procesada anteriormente.',
                    'cashTransfer' => null
                ];
            }

            $transfer->status = 'rejected';
            $transfer->save();

            return [
                'success' => true,
                'message' => 'Entrega rechazada. El dinero ha sido devuelto a la caja de origen.',
                'cashTransfer' => $transfer
            ];
        } catch (Exception $e) {
            Log::error('Error rejecting cash transfer: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al rechazar la entrega: ' . $e->getMessage(),
                'cashTransfer' => null
            ];
        }
    }
}
