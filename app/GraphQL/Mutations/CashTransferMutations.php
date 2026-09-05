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

    /**
     * Update an existing cash transfer
     */
    public function update($_, array $args)
    {
        try {
            $transfer = CashTransfer::findOrFail($args['id']);

            $updateData = [];
            if (isset($args['amount'])) {
                $updateData['amount'] = $args['amount'];
            }
            if (isset($args['notes'])) {
                $updateData['notes'] = $args['notes'];
            }
            if (isset($args['sender_cash_register_id'])) {
                $updateData['sender_cash_register_id'] = $args['sender_cash_register_id'];
            }
            if (isset($args['receiver_cash_register_id'])) {
                $updateData['receiver_cash_register_id'] = $args['receiver_cash_register_id'];
            }
            if (isset($args['status'])) {
                $updateData['status'] = $args['status'];
            }

            $transfer->update($updateData);

            return [
                'success' => true,
                'message' => 'Entrega de dinero actualizada exitosamente.',
                'cashTransfer' => $transfer->fresh()
            ];
        } catch (Exception $e) {
            Log::error('Error updating cash transfer: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar la entrega: ' . $e->getMessage(),
                'cashTransfer' => null
            ];
        }
    }

    /**
     * Cancel / Anular a cash transfer (reverts balances and records reason)
     */
    public function cancel($_, array $args)
    {
        try {
            $transfer = CashTransfer::findOrFail($args['id']);

            if ($transfer->status === 'cancelled') {
                return [
                    'success' => false,
                    'message' => 'Esta entrega ya se encuentra anulada.',
                    'cashTransfer' => $transfer
                ];
            }

            $reason = trim($args['reason'] ?? '');
            if (empty($reason)) {
                return [
                    'success' => false,
                    'message' => 'Debes proporcionar un motivo de anulación.',
                    'cashTransfer' => null
                ];
            }

            $notes = $transfer->notes ? $transfer->notes . "\n[MOTIVO ANULACIÓN]: " . $reason : "[MOTIVO ANULACIÓN]: " . $reason;

            $transfer->status = 'cancelled';
            $transfer->notes = $notes;
            $transfer->save();

            return [
                'success' => true,
                'message' => 'Entrega anulada exitosamente y saldos devueltos a las cajas correspondientes.',
                'cashTransfer' => $transfer
            ];
        } catch (Exception $e) {
            Log::error('Error cancelling cash transfer: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al anular la entrega: ' . $e->getMessage(),
                'cashTransfer' => null
            ];
        }
    }

    /**
     * Delete a cash transfer (reverts balances)
     */
    public function delete($_, array $args)
    {
        try {
            $transfer = CashTransfer::findOrFail($args['id']);
            $transfer->delete();

            return [
                'success' => true,
                'message' => 'Entrega eliminada exitosamente y saldos devueltos a las cajas correspondientes.'
            ];
        } catch (Exception $e) {
            Log::error('Error deleting cash transfer: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al eliminar la entrega: ' . $e->getMessage()
            ];
        }
    }
}
