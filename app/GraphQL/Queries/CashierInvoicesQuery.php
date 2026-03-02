<?php

namespace App\GraphQL\Queries;

use App\Models\Invoice\Invoice;
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
        $query = Invoice::query()
            ->where('payment_registered_by', $userId)
            ->where('status', Invoice::STATUS_PAID)
            ->whereIn('payment_method', ['cash', 'transfer']);

        $dateFrom = null;
        $dateTo = null;

        if (!empty($args['date'])) {
            $dateFrom = Carbon::parse($args['date'])->startOfDay();
            $dateTo = Carbon::parse($args['date'])->endOfDay();
            $query->whereBetween('payment_date', [$dateFrom, $dateTo]);
        } else {
            if (!empty($args['date_from'])) {
                $dateFrom = Carbon::parse($args['date_from'])->startOfDay();
                $query->where('payment_date', '>=', $dateFrom);
            } else {
                // Default to today if no date specified
                $dateFrom = Carbon::now()->startOfDay();
                $query->where('payment_date', '>=', $dateFrom);
            }

            if (!empty($args['date_to'])) {
                $dateTo = Carbon::parse($args['date_to'])->endOfDay();
                $query->where('payment_date', '<=', $dateTo);
            } else {
                 $dateTo = Carbon::now()->endOfDay();
                 $query->where('payment_date', '<=', $dateTo);
            }
        }

        // We get all to calculate summary fast. Since this is for a daily/filtered small range, it's efficient.
        $invoices = $query->get();

        $totalCash = $invoices->where('payment_method', 'cash')->sum('amount');
        $totalTransfer = $invoices->where('payment_method', 'transfer')->sum('amount');
        $totalCollected = $totalCash + $totalTransfer;

        return [
            'date_from' => $dateFrom ? $dateFrom->toDateString() : null,
            'date_to' => $dateTo ? $dateTo->toDateString() : null,
            'total_cash' => $totalCash,
            'total_transfer' => $totalTransfer,
            'total_collected' => $totalCollected,
            'total_invoices' => $invoices->count(),
        ];
    }
}
