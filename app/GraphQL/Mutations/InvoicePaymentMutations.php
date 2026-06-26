<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use App\Services\Invoice\InvoicePaymentService;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class InvoicePaymentMutations
{
    protected InvoicePaymentService $service;

    public function __construct(InvoicePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Crear un nuevo abono
     *
     * @param mixed $root
     * @param array<string, mixed> $args
     * @return \App\Models\Invoice\InvoicePayment
     * @throws InvalidArgumentException
     */
    public function create($root, array $args)
    {
        $dailyBoxId = $args['daily_box_id'] ?? null;
        if (!$dailyBoxId && Auth::check()) {
            $assignedRegister = \App\Models\Finance\CashRegister::where('user_id', Auth::id())->latest()->first();

            if (!$assignedRegister || $assignedRegister->status !== \App\Models\Finance\CashRegister::STATUS_OPEN) {
                throw new InvalidArgumentException(
                    'No puedes registrar pagos en este momento. La caja que tienes asignada se encuentra CERRADA o no tienes ninguna caja asignada. Por favor, abre tu caja para continuar.'
                );
            }
            $dailyBoxId = $assignedRegister->id;
        }

        // Preparar datos
        $data = [
            'invoice_id' => $args['invoice_id'],
            'user_id' => Auth::id(),
            'amount' => $args['amount'],
            'payment_date' => $args['payment_date'],
            'payment_method' => $args['payment_method'] ?? null,
            'payment_registered_by' => Auth::id(),
            'reference_number' => $args['reference_number'] ?? null,
            'notes' => $args['notes'] ?? null,
            'payment_support' => $args['payment_support'] ?? null,
            'daily_box_id' => $dailyBoxId,
        ];

        // Validar que el monto no exceda el saldo pendiente
        $invoice = Invoice::findOrFail($args['invoice_id']);

        if ($data['amount'] > $invoice->real_outstanding_balance) {
            throw new InvalidArgumentException(
                'El monto del abono (' . number_format($data['amount'], 2) . ') ' .
                'no puede ser mayor que el saldo pendiente (' .
                number_format($invoice->real_outstanding_balance, 2) . ')'
            );
        }

        return $this->service->save($data);
    }

    /**
     * Actualizar un abono existente
     *
     * @param mixed $root
     * @param array<string, mixed> $args
     * @return \App\Models\Invoice\InvoicePayment
     * @throws InvalidArgumentException
     */
    public function update($root, array $args)
    {
        $data = array_filter([
            'amount' => $args['amount'] ?? null,
            'payment_date' => $args['payment_date'] ?? null,
            'payment_method' => $args['payment_method'] ?? null,
            'reference_number' => $args['reference_number'] ?? null,
            'notes' => $args['notes'] ?? null,
        ], fn($value) => $value !== null);

        // Si se actualiza el monto, validar
        if (isset($data['amount'])) {
            $payment = $this->service->getById($args['id']);
            $invoice = $payment->invoice;

            // Calcular nuevo saldo considerando el cambio
            $currentPaymentTotal = $invoice->payments()->sum('amount');
            $newPaymentTotal = $currentPaymentTotal - $payment->amount + $data['amount'];

            if ($newPaymentTotal > $invoice->total) {
                throw new InvalidArgumentException(
                    'El nuevo monto causaría que los pagos totales (' .
                    number_format($newPaymentTotal, 2) .
                    ') excedan el total de la factura (' .
                    number_format($invoice->total, 2) . ')'
                );
            }
        }

        return $this->service->update($data, $args['id']);
    }

    /**
     * Eliminar un abono
     *
     * @param mixed $root
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     * @throws InvalidArgumentException
     */
    public function delete($root, array $args)
    {
        $payment = $this->service->getById($args['id']);
        $invoice = $payment->invoice;

        $this->service->deleteById($args['id']);

        // Recargar la factura actualizada
        $invoice->refresh();

        return [
            'success' => true,
            'message' => 'Abono eliminado correctamente',
            'invoice' => $invoice,
        ];
    }
}
