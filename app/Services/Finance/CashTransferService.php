<?php
namespace App\Services\Finance;

use App\Models\Finance\CashTransfer;
use App\Models\Finance\CashRegister;
use App\Models\Finance\Vault;
use Illuminate\Support\Facades\DB;
use Exception;

class CashTransferService
{
    /**
     * Send money from a sender to a receiver.
     */
    public function sendMoney($sender, $receiver, float $amount, string $notes = null): CashTransfer
    {
        DB::beginTransaction();
        try {
            // Determine if sender is a CashRegister or Vault
            if ($sender instanceof CashRegister) {
                if (!$sender->isOpen()) {
                    throw new Exception("La caja origen debe estar abierta para enviar dinero.");
                }
                if ($sender->current_balance < $amount) {
                    throw new Exception("Saldo insuficiente en la caja origen.");
                }
                $sender->decrement('current_balance', $amount);
            } elseif ($sender instanceof Vault) {
                if ($sender->current_balance < $amount) {
                    throw new Exception("Saldo insuficiente en la bóveda origen.");
                }
                $sender->decrement('current_balance', $amount);
            } else {
                throw new Exception("Tipo de origen inválido.");
            }

            // Create transfer
            $transfer = new CashTransfer();
            $transfer->sender()->associate($sender);
            $transfer->receiver()->associate($receiver);
            $transfer->amount = $amount;
            $transfer->status = 'pending';
            $transfer->notes = $notes;
            $transfer->save();

            DB::commit();
            return $transfer;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a pending transfer (adjust amounts and receivers).
     */
    public function updateTransfer(CashTransfer $transfer, array $data): CashTransfer
    {
        DB::beginTransaction();
        try {
            if ($transfer->status !== 'pending') {
                throw new Exception("Solo se pueden editar transferencias en estado pendiente.");
            }

            // Handle Amount change
            if (isset($data['amount']) && $data['amount'] != $transfer->amount) {
                $newAmount = (float)$data['amount'];
                $difference = $newAmount - $transfer->amount;
                $sender = $transfer->sender;

                if ($difference > 0) {
                    // Sender is sending MORE. We need to deduct the difference.
                    if ($sender->current_balance < $difference) {
                        throw new Exception("Saldo insuficiente en el origen para aumentar el monto de la transferencia.");
                    }
                    $sender->decrement('current_balance', $difference);
                } else {
                    // Sender is sending LESS. We need to refund the difference.
                    // difference is negative, so we add abs($difference)
                    $sender->increment('current_balance', abs($difference));
                }

                $transfer->amount = $newAmount;
            }

            // Handle Receiver change
            if (!empty($data['receiver_type']) && !empty($data['receiver_id'])) {
                $receiverClass = $data['receiver_type'] === 'Vault' ? Vault::class : CashRegister::class;
                $newReceiver = $receiverClass::findOrFail($data['receiver_id']);
                $transfer->receiver()->associate($newReceiver);
                
                // Legacy fields logic if needed
                if ($newReceiver instanceof CashRegister) {
                    $transfer->receiver_cash_register_id = $newReceiver->id;
                } else {
                    $transfer->receiver_cash_register_id = null;
                }
            } elseif (isset($data['receiver_cash_register_id'])) {
                // Fallback for older endpoints
                $newReceiver = CashRegister::findOrFail($data['receiver_cash_register_id']);
                $transfer->receiver()->associate($newReceiver);
                $transfer->receiver_cash_register_id = $newReceiver->id;
            }

            // Handle Notes change
            if (isset($data['notes'])) {
                $transfer->notes = $data['notes'];
            }

            $transfer->save();
            DB::commit();

            return $transfer;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Accept a transfer, potentially with a partial receipt/discrepancy.
     */
    public function acceptTransfer(CashTransfer $transfer, float $receivedAmount, string $discrepancyNote = null): CashTransfer
    {
        DB::beginTransaction();
        try {
            if ($transfer->status !== 'pending') {
                throw new Exception("La transferencia no está pendiente.");
            }

            $diff = $transfer->amount - $receivedAmount;
            if ($diff != 0 && empty($discrepancyNote)) {
                throw new Exception("Debe justificar la diferencia con una nota.");
            }

            // Update receiver balance
            $receiver = $transfer->receiver;
            if ($receiver instanceof CashRegister) {
                if (!$receiver->isOpen()) {
                    throw new Exception("La caja destino debe estar abierta para recibir el dinero.");
                }
                $receiver->increment('current_balance', $receivedAmount);
            } elseif ($receiver instanceof Vault) {
                $receiver->increment('current_balance', $receivedAmount);
            }

            $transfer->received_amount = $receivedAmount;
            $transfer->discrepancy_note = $discrepancyNote;
            $transfer->status = 'accepted';
            $transfer->save();

            DB::commit();
            return $transfer;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject a transfer completely.
     */
    public function rejectTransfer(CashTransfer $transfer, string $reason): CashTransfer
    {
        DB::beginTransaction();
        try {
            if ($transfer->status !== 'pending') {
                throw new Exception("La transferencia no está pendiente.");
            }

            // Return full money to sender
            $sender = $transfer->sender;
            if ($sender instanceof CashRegister) {
                $sender->increment('current_balance', $transfer->amount);
            } elseif ($sender instanceof Vault) {
                $sender->increment('current_balance', $transfer->amount);
            }

            $transfer->status = 'rejected';
            $transfer->discrepancy_note = $reason;
            $transfer->save();

            DB::commit();
            return $transfer;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
