<?php

namespace App\GraphQL\Queries;

use App\Models\Finance\Expense;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CashierInvoicesQuery
{
    /**
     * Resolve myCollectedInvoices query
     */
    public function myInvoices($root, array $args): Builder
    {
        $user = Auth::user();
        $userId = $user->id;
        $userName = $user->name;

        // Cuando se consulta una caja específica, omitir el filtro global de router.
        // La caja registradora ya actúa como filtro suficiente y debe mostrar
        // todos los recaudos de ese cajero independientemente del router asignado.
        $hasExplicitCashRegister = !empty($args['cash_register_id']);

        $query = $hasExplicitCashRegister
            ? Invoice::withoutGlobalScope('router_filter')->newQuery()
            : Invoice::query();

        if (!$hasExplicitCashRegister) {
            $query->where(function($q) use ($userId, $userName) {
                    $q->where('payment_registered_by', (string) $userId)
                      ->orWhere('payment_registered_by', $userName);
                });
        }
        
        $query->where('status', Invoice::STATUS_PAID);

        $cashRegister = null;

        if ($hasExplicitCashRegister) {
            $cashRegister = \App\Models\Finance\CashRegister::find($args['cash_register_id']);
        } else {
            // Auto-detect currently open cash register for the POS
            $cashRegister = \App\Models\Finance\CashRegister::where('user_id', $userId)
                ->where('status', \App\Models\Finance\CashRegister::STATUS_OPEN)
                ->latest()
                ->first();
        }

        if ($cashRegister) {
            $query->where('daily_box_id', $cashRegister->id);
        }

        // Filter by exact date
        if (!empty($args['date'])) {
            $date = Carbon::parse($args['date'])->toDateString();
            $query->whereDate('payment_date', $date);
        }

        // Filter by date range
        if (!empty($args['date_from'])) {
            $dateFrom = Carbon::parse($args['date_from'])->startOfDay();
            $query->where('payment_date', '>=', $dateFrom);
        }

        if (!empty($args['date_to'])) {
            $dateTo = Carbon::parse($args['date_to'])->endOfDay();
            $query->where('payment_date', '<=', $dateTo);
        }

        // Filter by specific payment method (cash/transfer usually)
        if (!empty($args['payment_method'])) {
            $query->where('payment_method', $args['payment_method']);
        }

        $query->orderBy('payment_date', 'desc');

        return $query;
    }

    /**
     * Resolve myDailyCollectionReport query
     */
    public function dailyReport($root, array $args)
    {
        $userId = Auth::id();

        $dateFrom = null;
        $dateTo = null;

        $cashRegister = null;

        if (!empty($args['cash_register_id'])) {
            $cashRegister = \App\Models\Finance\CashRegister::find($args['cash_register_id']);
        } else {
            $cashRegister = \App\Models\Finance\CashRegister::where('user_id', $userId)
                ->where('status', \App\Models\Finance\CashRegister::STATUS_OPEN)
                ->latest()
                ->first();
        }

        $currentUser = Auth::user();
        $userId = $currentUser->id;
        $registerUserFilter = $currentUser->name;

        // Detectar caja si se proporciona ID o si hay una abierta para el usuario
        if (!empty($args['cash_register_id'])) {
            $cashRegister = \App\Models\Finance\CashRegister::find($args['cash_register_id']);
        } else {
            $cashRegister = \App\Models\Finance\CashRegister::where('user_id', $userId)
                ->where('status', \App\Models\Finance\CashRegister::STATUS_OPEN)
                ->latest()
                ->first();
        }

        $dailyBoxId = $cashRegister ? $cashRegister->id : null;

        // Determinar el rango de fechas (Siempre respetar el día actual o el solicitado)
        if (!empty($args['date'])) {
            $dateFrom = Carbon::parse($args['date'])->startOfDay();
            $dateTo   = Carbon::parse($args['date'])->endOfDay();
        } else {
            if (!empty($args['date_from'])) {
                $dateFrom = Carbon::parse($args['date_from'])->startOfDay();
            } else {
                $dateFrom = Carbon::now()->startOfDay();
            }

            if (!empty($args['date_to'])) {
                $dateTo = Carbon::parse($args['date_to'])->endOfDay();
            } else {
                $dateTo = Carbon::now()->endOfDay();
            }
        }


        // ---- Pagos completos de facturas (invoice.status = paid) ----
        // Para facturas que tenían abonos previos, `amount` acumula todos los pagos
        // (abonos + pago final). Para evitar doble conteo, restamos la suma de abonos
        // (InvoicePayment) de cada factura, obteniendo solo lo cobrado en este día.
        $invoiceQuery = Invoice::query()
            ->with(['payments'])                                  // eager load abonos
            ->where(function($q) use ($userId, $registerUserFilter) {
                $q->where('payment_registered_by', (string) $userId)
                  ->orWhere('payment_registered_by', $registerUserFilter);
            })
            ->where('status', Invoice::STATUS_PAID)
            ->whereIn('payment_method', ['cash', 'transfer']);

        if ($dailyBoxId) {
            $invoiceQuery->where('daily_box_id', $dailyBoxId);
        }
        $invoiceQuery->whereBetween('payment_date', [$dateFrom, $dateTo]);

        $invoices = $invoiceQuery->get();

        // Por cada factura, el monto real del pago final del día es:
        //   amount (total acumulado) - suma de abonos (InvoicePayment) ya registrados
        // Los abonos se contabilizan por separado, así que no los sumamos aquí.
        $totalCashInvoices = $invoices
            ->where('payment_method', 'cash')
            ->sum(fn ($inv) => max(0, $inv->amount - $inv->payments->sum('amount')));

        $totalTransferInvoices = $invoices
            ->where('payment_method', 'transfer')
            ->sum(fn ($inv) => max(0, $inv->amount - $inv->payments->sum('amount')));

        $totalInvoicesCount = $invoices->count();

        // ---- Abonos parciales (InvoicePayment) ----
        $paymentsQuery = InvoicePayment::query()
            ->where('user_id', $userId)
            ->whereIn('payment_method', ['cash', 'transfer']);

        if ($dailyBoxId) {
            $paymentsQuery->where('daily_box_id', $dailyBoxId);
        }
        $paymentsQuery->whereBetween('payment_date', [$dateFrom, $dateTo]);

        $payments = $paymentsQuery->get();

        $totalCashPayments     = $payments->where('payment_method', 'cash')->sum('amount');
        $totalTransferPayments = $payments->where('payment_method', 'transfer')->sum('amount');
        $totalPaymentsCount    = $payments->count();
        $totalAbonos           = $totalCashPayments + $totalTransferPayments;

        // ---- Gastos (Expense) ----
        $expensesQuery = Expense::query();
        if ($dailyBoxId) {
            $expensesQuery->where('daily_box_id', $dailyBoxId);
        } else {
            // Si no hay caja abierta, filtrar por los gastos de las cajas del usuario
            $expensesQuery->whereHas('dailyBox', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        
        $expensesQuery->whereBetween('date', [$dateFrom, $dateTo]);
        
        $expenses = $expensesQuery->get();

        $totalExpenses = $expenses->sum('amount');
        $totalCashExpenses = $expenses->where('payment_method', 'cash')->sum('amount');
        $totalTransferExpenses = $expenses->where('payment_method', 'transfer')->sum('amount');

        // ---- Entregas a Administrador (Transfers Out) ----
        $transfersOutQuery = \App\Models\Finance\CashTransfer::query()
            ->where('status', '!=', 'rejected'); // Only pending or accepted

        if ($dailyBoxId) {
            $transfersOutQuery->where('sender_cash_register_id', $dailyBoxId);
        } else {
            $transfersOutQuery->whereHas('senderCashRegister', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        $transfersOutQuery->whereBetween('created_at', [$dateFrom, $dateTo]);

        $transfersOut = $transfersOutQuery->get();
        $totalTransfersOut = $transfersOut->sum('amount');

        // ---- Recepciones del Administrador (Transfers In) ----
        $transfersInQuery = \App\Models\Finance\CashTransfer::query()
            ->where('status', 'accepted'); // Only accepted

        if ($dailyBoxId) {
            $transfersInQuery->where('receiver_cash_register_id', $dailyBoxId);
        } else {
            $transfersInQuery->whereHas('receiverCashRegister', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        $transfersInQuery->whereBetween('updated_at', [$dateFrom, $dateTo]);

        $transfersIn = $transfersInQuery->get();
        // sumamos amount de las transferencias entrantes aceptadas
        $totalTransfersIn = $transfersIn->sum('amount');

        // ---- Totales combinados ----
        $totalCash      = $totalCashInvoices + $totalCashPayments;
        $totalTransfer  = $totalTransferInvoices + $totalTransferPayments;
        $totalCollected = $totalCash + $totalTransfer;
        $totalItems     = $totalInvoicesCount + $totalPaymentsCount;

        $initialBalance = $cashRegister ? (float) $cashRegister->initial_balance : 0;

        return [
            'date_from'       => $dateFrom ? $dateFrom->toDateString() : null,
            'date_to'         => $dateTo   ? $dateTo->toDateString()   : null,
            'initial_balance' => $initialBalance,
            'total_cash'      => $totalCash,
            'total_transfer'  => $totalTransfer,
            'total_collected' => $totalCollected,
            'total_invoices'          => $totalItems,
            'total_expenses'          => $totalExpenses,
            'total_cash_expenses'     => $totalCashExpenses,
            'total_transfer_expenses' => $totalTransferExpenses,
            'total_transfers_out'     => $totalTransfersOut,
            'total_transfers_in'      => $totalTransfersIn,
            'total_abonos'            => $totalAbonos,
            // Total Neto modificado para restar entregas a admin y sumar entregas recibidas (Recaudos admin)
            'total_neto'              => $initialBalance + $totalCollected - $totalExpenses - $totalTransfersOut + $totalTransfersIn,
        ];
    }

    /**
     * Resolve myExpenses query
     */
    public function myExpenses($root, array $args)
    {
        $user = Auth::user();
        $userId = $user->id;

        $query = Expense::query()->with(['expenseCategory', 'supplier', 'user']);

        if (!empty($args['daily_box_id'])) {
            // Si se filtra por caja específica, no aplicar ningún otro filtro de usuario
            $query->where('daily_box_id', $args['daily_box_id']);
        } else {
            // Regla de visibilidad por zona:
            // - Usuario SIN zona asignada → ve todos los gastos
            // - Usuario CON zona asignada → solo ve los gastos que él mismo registró
            if ($user->shouldFilterByRouter()) {
                $query->where('user_id', $userId);
            }
            // Si canSeeAllData() → no se aplica ningún filtro de usuario/caja
        }

        // Filtro por categoría
        if (!empty($args['expense_category_id'])) {
            $query->where('expense_category_id', $args['expense_category_id']);
        }

        // Filtro por usuario que registró el gasto
        if (!empty($args['user_id'])) {
            $query->where('user_id', $args['user_id']);
        }

        if (!empty($args['date'])) {
            $query->whereDate('date', $args['date']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
