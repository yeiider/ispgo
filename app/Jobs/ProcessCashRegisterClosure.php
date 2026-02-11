<?php

namespace App\Jobs;

use App\Models\Finance\CashRegister;
use App\Models\Finance\CashRegisterClosure;
use App\Models\Invoice\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Job para procesar el cierre de caja de forma asíncrona con Redis
 */
class ProcessCashRegisterClosure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300; // 5 minutos

    protected int $cashRegisterId;
    protected int $userId;
    protected Carbon $closureDate;
    protected ?float $closingBalance;
    protected ?string $notes;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $cashRegisterId,
        int $userId,
        Carbon $closureDate,
        ?float $closingBalance = null,
        ?string $notes = null
    ) {
        $this->cashRegisterId = $cashRegisterId;
        $this->userId = $userId;
        $this->closureDate = $closureDate;
        $this->closingBalance = $closingBalance;
        $this->notes = $notes;

        // Configurar cola Redis
        $this->onQueue('cash-register-closures');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $cashRegister = CashRegister::findOrFail($this->cashRegisterId);

            // Verificar si ya existe un cierre para esta fecha
            $existingClosure = CashRegisterClosure::where('cash_register_id', $this->cashRegisterId)
                ->whereDate('closure_date', $this->closureDate)
                ->first();

            if ($existingClosure && $existingClosure->status === CashRegisterClosure::STATUS_COMPLETED) {
                Log::warning('Ya existe un cierre completado para esta fecha', [
                    'cash_register_id' => $this->cashRegisterId,
                    'closure_date' => $this->closureDate,
                ]);
                DB::commit();
                return;
            }

            // Crear o actualizar el cierre
            $closure = $existingClosure ?? new CashRegisterClosure();
            $closure->cash_register_id = $this->cashRegisterId;
            $closure->user_id = $this->userId;
            $closure->closure_date = $this->closureDate;
            $closure->opening_balance = $cashRegister->initial_balance;
            $closure->status = CashRegisterClosure::STATUS_PROCESSING;
            $closure->save();

            // Obtener facturas pagadas del día para esta caja
            $invoices = Invoice::where('daily_box_id', $this->cashRegisterId)
                ->whereDate('updated_at', $this->closureDate)
                ->where('status', Invoice::STATUS_PAID)
                ->with(['adjustments', 'customer'])
                ->get();

            // Calcular totales por método de pago
            $totalCash = $invoices->where('payment_method', 'cash')->sum('amount');
            $totalTransfer = $invoices->where('payment_method', 'transfer')->sum('amount');
            $totalCard = $invoices->where('payment_method', 'card')->sum('amount');
            $totalOnline = $invoices->where('payment_method', 'online')->sum('amount');
            $totalOther = $invoices->whereIn('payment_method', ['other', 'check', 'cryptocurrency'])->sum('amount');

            // Calcular totales generales
            $totalCollected = $invoices->sum('amount');
            $totalDiscounts = $invoices->sum('discount');
            $totalAdjustments = $invoices->sum(function ($invoice) {
                return $invoice->adjustments->sum('amount');
            });

            // Generar detalles de pago
            $paymentDetails = $this->generatePaymentDetails($invoices);

            // Generar resumen de facturas
            $invoiceSummary = $this->generateInvoiceSummary($invoices);

            // Calcular balance esperado
            $expectedBalance = $closure->opening_balance + $totalCollected;
            $closingBalance = $this->closingBalance ?? $expectedBalance;
            $difference = $closingBalance - $expectedBalance;

            // Actualizar el cierre con todos los datos
            $closure->update([
                'closing_balance' => $closingBalance,
                'expected_balance' => $expectedBalance,
                'difference' => $difference,
                'total_cash' => $totalCash,
                'total_transfer' => $totalTransfer,
                'total_card' => $totalCard,
                'total_online' => $totalOnline,
                'total_other' => $totalOther,
                'total_invoices' => $invoices->count(),
                'paid_invoices' => $invoices->where('status', Invoice::STATUS_PAID)->count(),
                'total_collected' => $totalCollected,
                'total_discounts' => $totalDiscounts,
                'total_adjustments' => $totalAdjustments,
                'payment_details' => $paymentDetails,
                'invoice_summary' => $invoiceSummary,
                'notes' => $this->notes,
            ]);

            // Marcar como completado
            $closure->markAsCompleted();

            // Actualizar estado de la caja
            $cashRegister->close();
            $cashRegister->current_balance = $closingBalance;
            $cashRegister->save();

            DB::commit();

            Log::info('Cierre de caja procesado exitosamente', [
                'closure_id' => $closure->id,
                'cash_register_id' => $this->cashRegisterId,
                'total_collected' => $totalCollected,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al procesar cierre de caja', [
                'cash_register_id' => $this->cashRegisterId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (isset($closure)) {
                $closure->markAsFailed($e->getMessage());
            }

            throw $e;
        }
    }

    /**
     * Generar detalles de pago por método
     */
    protected function generatePaymentDetails($invoices): array
    {
        $paymentMethods = ['cash', 'transfer', 'card', 'online', 'other', 'check', 'cryptocurrency'];
        $details = [];

        foreach ($paymentMethods as $method) {
            $methodInvoices = $invoices->where('payment_method', $method);
            $details[$method] = [
                'count' => $methodInvoices->count(),
                'total' => $methodInvoices->sum('amount'),
                'invoices' => $methodInvoices->pluck('increment_id')->toArray(),
            ];
        }

        return $details;
    }

    /**
     * Generar resumen de facturas
     */
    protected function generateInvoiceSummary($invoices): array
    {
        return [
            'total_invoices' => $invoices->count(),
            'paid_invoices' => $invoices->where('status', Invoice::STATUS_PAID)->count(),
            'total_amount' => $invoices->sum('total'),
            'total_paid' => $invoices->sum('amount'),
            'total_discounts' => $invoices->sum('discount'),
            'average_ticket' => $invoices->count() > 0 ? $invoices->sum('amount') / $invoices->count() : 0,
            'customers' => $invoices->pluck('customer.first_name')->unique()->count(),
            'invoices_with_discount' => $invoices->where('discount', '>', 0)->count(),
            'invoices_with_adjustments' => $invoices->filter(function ($invoice) {
                return $invoice->adjustments->count() > 0;
            })->count(),
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de cierre de caja falló después de todos los intentos', [
            'cash_register_id' => $this->cashRegisterId,
            'error' => $exception->getMessage(),
        ]);

        try {
            $closure = CashRegisterClosure::where('cash_register_id', $this->cashRegisterId)
                ->whereDate('closure_date', $this->closureDate)
                ->first();

            if ($closure) {
                $closure->markAsFailed($exception->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error al marcar cierre como fallido', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
