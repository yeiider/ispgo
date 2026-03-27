<?php

namespace App\Jobs;

use App\Models\Finance\CashRegister;
use App\Models\Finance\CashRegisterClosure;
use App\Models\Finance\Expense;
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

            // Crear o actualizar el cierre
            $closure = $existingClosure ?? new CashRegisterClosure();
            $closure->cash_register_id = $this->cashRegisterId;
            $closure->user_id = $this->userId;
            $closure->closure_date = $this->closureDate;
            $closure->opening_balance = $existingClosure ? $existingClosure->opening_balance : $cashRegister->initial_balance;
            $closure->status = CashRegisterClosure::STATUS_PROCESSING;
            $closure->save();

            $dateFrom = $cashRegister->opened_at;
            $dateTo = $cashRegister->closed_at ?? Carbon::now();

            $userId = $cashRegister->user_id;
            $userName = $cashRegister->user ? $cashRegister->user->name : null;

            // Obtener facturas pagadas asociadas a esta caja para esta fecha específica
            $invoices = Invoice::where('daily_box_id', $this->cashRegisterId)
                ->where('status', Invoice::STATUS_PAID)
                ->whereDate('payment_date', $this->closureDate)
                ->with(['adjustments', 'customer', 'payments'])
                ->get();

            // Alternativa por si hay facturas sin daily_box_id pero con fecha dentro del rango (compatibilidad)
            if ($invoices->isEmpty()) {
                $invoices = Invoice::where(function($q) use ($userId, $userName) {
                        $q->where('payment_registered_by', (string) $userId);
                        if ($userName) $q->orWhere('payment_registered_by', $userName);
                    })
                    ->whereBetween('payment_date', [$dateFrom, $dateTo])
                    ->where('status', Invoice::STATUS_PAID)
                    ->whereNull('daily_box_id')
                    ->with(['adjustments', 'customer', 'payments'])
                    ->get();
            }

            // Obtener abonos parciales asociados a esta caja para esta fecha específica
            $invoicePayments = \App\Models\Invoice\InvoicePayment::where('daily_box_id', $this->cashRegisterId)
                ->whereDate('payment_date', $this->closureDate)
                ->with(['invoice'])
                ->get();

            if ($invoicePayments->isEmpty()) {
                $invoicePayments = \App\Models\Invoice\InvoicePayment::where('user_id', $cashRegister->user_id)
                    ->whereBetween('payment_date', [$dateFrom, $dateTo])
                    ->whereNull('daily_box_id')
                    ->with(['invoice'])
                    ->get();
            }

            // Obtener gastos asociados a esta caja para esta fecha específica
            $expenses = Expense::where('daily_box_id', $this->cashRegisterId)
                ->whereDate('date', $this->closureDate)
                ->get();

            // Obtener entregas a administradores (salidas)
            $transfersOut = \App\Models\Finance\CashTransfer::where('sender_cash_register_id', $this->cashRegisterId)
                ->whereDate('created_at', $this->closureDate)
                ->get();

            // Obtener entregas recibidas de cajeros (entradas)
            $transfersIn = \App\Models\Finance\CashTransfer::where('receiver_cash_register_id', $this->cashRegisterId)
                ->where('status', 'accepted')
                ->whereDate('updated_at', $this->closureDate)
                ->get();

            $paymentMethods = ['cash', 'transfer', 'card', 'online', 'other', 'check', 'cryptocurrency'];
            
            $totalsByMethod = [];
            $totalCollected = 0;
            
            foreach ($paymentMethods as $method) {
                $methodInvoices = $invoices->where('payment_method', $method);
                $invAmount = $methodInvoices->sum(fn ($inv) => max(0, $inv->amount - $inv->payments->sum('amount')));
                
                $methodAbonos = $invoicePayments->where('payment_method', $method);
                $abonoAmount = $methodAbonos->sum('amount');
                
                $methodTotal = $invAmount + $abonoAmount;
                
                $totalsByMethod[$method] = $methodTotal;
                $totalCollected += $methodTotal;
            }

            $totalCash = $totalsByMethod['cash'];
            $totalTransfer = $totalsByMethod['transfer'];
            $totalCard = $totalsByMethod['card'];
            $totalOnline = $totalsByMethod['online'];
            $totalOther = $totalsByMethod['other'] + $totalsByMethod['check'] + $totalsByMethod['cryptocurrency'];

            $totalDiscounts = $invoices->sum('discount');
            $totalAdjustments = $invoices->sum(function ($invoice) {
                return $invoice->adjustments->sum('amount');
            });

            // Generar detalles de pago
            $paymentDetails = $this->generatePaymentDetails($invoices, $invoicePayments, $totalsByMethod);

            // Generar resumen de facturas
            $totalCashExpenses = $expenses->where('payment_method', 'cash')->sum('amount');
            $totalExpenses = $expenses->sum('amount');
            $totalTransfersOut = $transfersOut->sum('amount');
            $totalTransfersIn = $transfersIn->sum('amount');
            $totalAbonos   = $invoicePayments->sum('amount');
            $invoiceSummary = $this->generateInvoiceSummary($invoices, $invoicePayments, $totalCollected);
            $invoiceSummary['total_expenses'] = $totalExpenses;
            $invoiceSummary['total_cash_expenses'] = $totalCashExpenses;
            $invoiceSummary['total_transfers_out'] = $totalTransfersOut;
            $invoiceSummary['total_transfers_in'] = $totalTransfersIn;

            // Calcular balance esperado (SOLO EFECTIVO): Apertura + Recaudado Efectivo - Gastos Efectivo - Entregas a Admin + Recepciones Acepatadas
            $expectedBalance = $closure->opening_balance + $totalCash - $totalCashExpenses - $totalTransfersOut + $totalTransfersIn;
            $closingBalance = $this->closingBalance ?? $expectedBalance;
            $difference = $closingBalance - $expectedBalance;

            // Actualizar el cierre con todos los datos
            $closure->update([
                'closing_balance'   => $closingBalance,
                'expected_balance'  => $expectedBalance,
                'difference'        => $difference,
                'total_cash'        => $totalCash,
                'total_transfer'    => $totalTransfer,
                'total_card'        => $totalCard,
                'total_online'      => $totalOnline,
                'total_other'       => $totalOther,
                'total_abonos'      => $totalAbonos,
                'total_expenses'    => $totalExpenses,
                'total_transfers_out' => $totalTransfersOut,
                'total_transfers_in' => $totalTransfersIn,
                'total_invoices'    => $invoices->count() + $invoicePayments->count(),
                'paid_invoices'     => $invoices->count(),
                'total_collected'   => $totalCollected,
                'total_discounts'   => $totalDiscounts,
                'total_adjustments' => $totalAdjustments,
                'payment_details'   => $paymentDetails,
                'invoice_summary'   => $invoiceSummary,
                'notes'             => $this->notes,
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
    protected function generatePaymentDetails($invoices, $invoicePayments, $totalsByMethod): array
    {
        $paymentMethods = ['cash', 'transfer', 'card', 'online', 'other', 'check', 'cryptocurrency'];
        $details = [];

        foreach ($paymentMethods as $method) {
            $methodInvoices = $invoices->where('payment_method', $method);
            $methodAbonos = $invoicePayments->where('payment_method', $method);
            
            $invIds = $methodInvoices->pluck('increment_id')->toArray();
            $abonoInvIds = $methodAbonos->map(function($abono) {
                return $abono->invoice ? $abono->invoice->increment_id : null;
            })->filter()->toArray();
            
            $details[$method] = [
                'count' => $methodInvoices->count() + $methodAbonos->count(),
                'total' => $totalsByMethod[$method],
                'invoices' => array_values(array_unique(array_merge($invIds, $abonoInvIds))),
            ];
        }

        return $details;
    }

    /**
     * Generar resumen de facturas
     */
    protected function generateInvoiceSummary($invoices, $invoicePayments, $totalCollected): array
    {
        $totalTransactions = $invoices->count() + $invoicePayments->count();
        return [
            'total_invoices' => $totalTransactions,
            'paid_invoices' => $invoices->where('status', Invoice::STATUS_PAID)->count(),
            'partial_payments' => $invoicePayments->count(),
            'total_amount' => $invoices->sum('total') + $invoicePayments->sum('amount'),
            'total_paid' => $totalCollected,
            'total_discounts' => $invoices->sum('discount'),
            'average_ticket' => $totalTransactions > 0 ? $totalCollected / $totalTransactions : 0,
            'customers' => $invoices->pluck('customer.first_name')->merge($invoicePayments->map(fn($p) => $p->invoice?->customer?->first_name))->unique()->count(),
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
