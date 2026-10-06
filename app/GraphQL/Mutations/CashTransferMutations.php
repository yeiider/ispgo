<?php

namespace App\GraphQL\Mutations;

use App\Models\Finance\CashTransfer;
use App\Models\Finance\CashRegister;
use App\Models\Finance\Vault;
use App\Services\Finance\CashTransferService;
use Exception;
use Illuminate\Support\Facades\Log;

class CashTransferMutations
{
    protected $transferService;

    public function __construct(CashTransferService $transferService)
    {
        $this->transferService = $transferService;
    }
    /**
     * Create a new cash transfer (delivery to admin)
     */
    public function create($_, array $args)
    {
        try {
            // Resolver Sender
            $sender = null;
            if (!empty($args['sender_type']) && !empty($args['sender_id'])) {
                $senderClass = $args['sender_type'] === 'Vault' ? Vault::class : CashRegister::class;
                $sender = $senderClass::findOrFail($args['sender_id']);
            } else {
                $sender = CashRegister::findOrFail($args['sender_cash_register_id']);
            }

            // Resolver Receiver
            $receiver = null;
            if (!empty($args['receiver_type']) && !empty($args['receiver_id'])) {
                $receiverClass = $args['receiver_type'] === 'Vault' ? Vault::class : CashRegister::class;
                $receiver = $receiverClass::findOrFail($args['receiver_id']);
            } else {
                $receiver = CashRegister::findOrFail($args['receiver_cash_register_id']);
            }

            $notes = $args['notes'] ?? null;
            $transfer = $this->transferService->sendMoney($sender, $receiver, $args['amount'], $notes);

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
            $receivedAmount = $args['received_amount'] ?? $transfer->amount;
            $note = $args['discrepancy_note'] ?? null;

            $this->transferService->acceptTransfer($transfer, $receivedAmount, $note);

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
            $reason = $args['reason'] ?? 'Rechazado por el administrador.';

            $this->transferService->rejectTransfer($transfer, $reason);

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

            $transfer = $this->transferService->updateTransfer($transfer, $args);

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

            // Usamos el servicio de anulación/rechazo para devolver el saldo correctamente
            $this->transferService->rejectTransfer($transfer, "[MOTIVO ANULACIÓN]: " . $reason);
            // El servicio lo marca como 'rejected'. Lo actualizamos a 'cancelled'
            $transfer->status = 'cancelled';
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
