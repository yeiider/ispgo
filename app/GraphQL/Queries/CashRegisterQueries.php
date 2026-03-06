<?php

namespace App\GraphQL\Queries;

use App\Models\Finance\CashRegister;
use App\Models\Finance\CashRegisterClosure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CashRegisterQueries
{
    /**
     * Obtener cierres de caja con filtros
     */
    public function closures($root, array $args)
    {
        $query = CashRegisterClosure::query()->with(['cashRegister', 'user']);

        // Filtrar por caja
        if (isset($args['cashRegisterId'])) {
            $query->where('cash_register_id', $args['cashRegisterId']);
        }

        // Filtrar por estado
        if (isset($args['status'])) {
            $query->where('status', $args['status']);
        }

        // Filtrar por rango de fechas
        if (isset($args['dateFrom']) && isset($args['dateTo'])) {
            $dateFrom = Carbon::parse($args['dateFrom']);
            $dateTo = Carbon::parse($args['dateTo']);
            $query->dateRange($dateFrom, $dateTo);
        } elseif (isset($args['dateFrom'])) {
            $dateFrom = Carbon::parse($args['dateFrom']);
            $query->where('closure_date', '>=', $dateFrom);
        } elseif (isset($args['dateTo'])) {
            $dateTo = Carbon::parse($args['dateTo']);
            $query->where('closure_date', '<=', $dateTo);
        }

        // Aplicar ordenamiento
        if (isset($args['orderBy'])) {
            foreach ($args['orderBy'] as $order) {
                $query->orderBy($order['column'], $order['order']);
            }
        } else {
            $query->orderBy('closure_date', 'desc');
        }

        return $query;
    }

    /**
     * Generar reporte consolidado de cierres
     */
    public function closureReport($root, array $args)
    {
        $dateFrom = Carbon::parse($args['dateFrom']);
        $dateTo = Carbon::parse($args['dateTo']);

        $query = CashRegisterClosure::query()
            ->completed()
            ->dateRange($dateFrom, $dateTo);

        // Filtrar por caja si se especifica
        if (isset($args['cashRegisterId'])) {
            $query->where('cash_register_id', $args['cashRegisterId']);
        }

        $closures = $query->get();

        // Si no hay cierres, retornar valores en cero
        if ($closures->isEmpty()) {
            return [
                'totalClosures' => 0,
                'totalCollected' => 0,
                'totalDiscounts' => 0,
                'paymentMethodTotals' => [
                    'cash' => 0,
                    'transfer' => 0,
                    'card' => 0,
                    'online' => 0,
                    'other' => 0,
                ],
                'totalInvoices' => 0,
                'averagePerClosure' => 0,
                'closuresWithDifferences' => 0,
            ];
        }

        $totalClosures = $closures->count();
        $totalCollected = $closures->sum('total_collected');
        $totalDiscounts = $closures->sum('total_discounts');
        $totalInvoices = $closures->sum('total_invoices');
        $closuresWithDifferences = $closures->filter(function ($closure) {
            return abs($closure->difference) > 0.01;
        })->count();

        return [
            'totalClosures' => $totalClosures,
            'totalCollected' => $totalCollected,
            'totalDiscounts' => $totalDiscounts,
            'paymentMethodTotals' => [
                'cash' => $closures->sum('total_cash'),
                'transfer' => $closures->sum('total_transfer'),
                'card' => $closures->sum('total_card'),
                'online' => $closures->sum('total_online'),
                'other' => $closures->sum('total_other'),
            ],
            'totalInvoices' => $totalInvoices,
            'averagePerClosure' => $totalClosures > 0 ? $totalCollected / $totalClosures : 0,
            'closuresWithDifferences' => $closuresWithDifferences,
        ];
    }
}
