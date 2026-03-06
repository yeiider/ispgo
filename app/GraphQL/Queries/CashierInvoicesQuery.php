<?php

namespace App\GraphQL\Queries;

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
        $userId = Auth::id();
        $query = Invoice::query()
            ->where('payment_registered_by', $userId)
            ->where('status', Invoice::STATUS_PAID);

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
            ->where('payment_registered_by', $userId)
            ->where('status', Invoice::STATUS_PAID)
            ->whereIn('payment_method', ['cash', 'transfer'])
            ->whereBetween('payment_date', [$dateFrom, $dateTo]);

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
            ->whereIn('payment_method', ['cash', 'transfer'])
            ->whereBetween('payment_date', [$dateFrom, $dateTo]);

        $payments = $paymentsQuery->get();

        $totalCashPayments     = $payments->where('payment_method', 'cash')->sum('amount');
        $totalTransferPayments = $payments->where('payment_method', 'transfer')->sum('amount');
        $totalPaymentsCount    = $payments->count();

        // ---- Totales combinados ----
        $totalCash      = $totalCashInvoices + $totalCashPayments;
        $totalTransfer  = $totalTransferInvoices + $totalTransferPayments;
        $totalCollected = $totalCash + $totalTransfer;
        $totalItems     = $totalInvoicesCount + $totalPaymentsCount;

        return [
            'date_from'       => $dateFrom ? $dateFrom->toDateString() : null,
            'date_to'         => $dateTo   ? $dateTo->toDateString()   : null,
            'total_cash'      => $totalCash,
            'total_transfer'  => $totalTransfer,
            'total_collected' => $totalCollected,
            'total_invoices'  => $totalItems,
        ];
    }
}
