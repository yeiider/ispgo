<?php

namespace App\GraphQL\Queries;

use App\Models\Invoice\InvoicePayment;
use App\Services\Invoice\InvoicePaymentService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InvoicePaymentQueries
{
    protected InvoicePaymentService $service;

    public function __construct(InvoicePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtener abonos de una factura específica
     *
     * @param mixed $root
     * @param array<string, mixed> $args
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByInvoice($root, array $args)
    {
        return $this->service->getByInvoiceId($args['invoiceId']);
    }

    /**
     * Obtener abonos registrados por el cajero autenticado (para historial de caja)
     *
     * @param mixed $root
     * @param array<string, mixed> $args
     * @return Builder
     */
    public function myPayments($root, array $args): Builder
    {
        $user = Auth::user();
        $userId = $user->id;
        $userName = $user->name;

        $query = InvoicePayment::query()->where(function($q) use ($userId, $userName) {
            $q->where('user_id', $userId)
              ->orWhere('payment_registered_by', $userName);
        });

        $cashRegister = null;

        if (!empty($args['cash_register_id'])) {
            $cashRegister = \App\Models\Finance\CashRegister::find($args['cash_register_id']);
        } else {
            // Auto-detect currently open cash register for the POS
            $cashRegister = \App\Models\Finance\CashRegister::where('user_id', $userId)
                ->where('status', \App\Models\Finance\CashRegister::STATUS_OPEN)
                ->first();
        }

        if ($cashRegister) {
            $query->where('daily_box_id', $cashRegister->id);
        } else {
            // Filtrar por fecha exacta
            if (!empty($args['date'])) {
                $date = Carbon::parse($args['date'])->toDateString();
                $query->whereDate('payment_date', $date);
            }

            // Filtrar por rango de fechas
            if (!empty($args['date_from'])) {
                $dateFrom = Carbon::parse($args['date_from'])->startOfDay();
                $query->where('payment_date', '>=', $dateFrom);
            }

            if (!empty($args['date_to'])) {
                $dateTo = Carbon::parse($args['date_to'])->endOfDay();
                $query->where('payment_date', '<=', $dateTo);
            }
        }

        // Filtrar por método de pago
        if (!empty($args['payment_method'])) {
            $query->where('payment_method', $args['payment_method']);
        }

        $query->orderBy('payment_date', 'desc');

        return $query;
    }
}
