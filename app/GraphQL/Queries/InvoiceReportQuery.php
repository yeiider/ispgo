<?php

namespace App\GraphQL\Queries;

use App\Models\Invoice\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InvoiceReportQuery
{
    public function resolve($root, array $args)
    {
        $dateFrom = isset($args['date_from']) ? Carbon::parse($args['date_from']) : Carbon::now()->startOfMonth();
        $dateTo = isset($args['date_to']) ? Carbon::parse($args['date_to']) : Carbon::now()->endOfMonth();
        $statuses = $args['status'] ?? null;
        $paymentMethods = $args['payment_method'] ?? null;
        $chartFrequency = $args['chart_frequency'] ?? 'daily';

        $query = Invoice::query()
            ->whereBetween('issue_date', [$dateFrom->toDateString(), $dateTo->toDateString()]);

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        if (!empty($paymentMethods)) {
            $query->whereIn('payment_method', $paymentMethods);
        }

        // Clone query for different aggregations to avoid mutating the base query state
        $summaryQuery = clone $query;
        $chartQuery = clone $query;
        $statusQuery = clone $query;
        $paymentMethodQuery = clone $query;

        // --- Summary Calculation ---
        $summaryData = $summaryQuery->selectRaw('
            COUNT(*) as total_invoices,
            SUM(total) as total_amount,
            SUM(amount) as total_paid,
            SUM(outstanding_balance) as total_outstanding,
            SUM(discount) as total_discount,
            SUM(tax) as total_tax,
            SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_count,
            SUM(CASE WHEN status = "unpaid" THEN 1 ELSE 0 END) as unpaid_count,
            SUM(CASE WHEN status = "overdue" THEN 1 ELSE 0 END) as overdue_count,
            SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as canceled_count
        ')->first();

         // Note: canceled_count check above might be wrong if status is 'canceled', adjusting below if needed, 
         // assuming status 'canceled' exists based on typical invoice logic, though model analysis showed 'canceled' method.
         // Let's re-verify status for canceled. The model has a canceled() method setting status to 'canceled'. 
         // So I will use 'canceled' string.

        $summary = [
            'total_invoices' => $summaryData->total_invoices ?? 0,
            'total_amount' => $summaryData->total_amount ?? 0,
            'total_paid' => $summaryData->total_paid ?? 0,
            'total_outstanding' => $summaryData->total_outstanding ?? 0,
            'total_discount' => $summaryData->total_discount ?? 0,
            'total_tax' => $summaryData->total_tax ?? 0,
            'paid_count' => $summaryData->paid_count ?? 0,
            'unpaid_count' => $summaryData->unpaid_count ?? 0,
            'overdue_count' => $summaryData->overdue_count ?? 0,
            'canceled_count' => $summaryQuery->clone()->where('status', 'canceled')->count(), // Separate count to be safe or use raw query properly
        ];
        
        // --- Charts Data ---
        
        // 1. Revenue/Trends Over Time
        $dateFormat = match ($chartFrequency) {
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
            default => '%Y-%m-%d',
        };

        $revenueData = $chartQuery
             ->select(DB::raw("DATE_FORMAT(issue_date, '$dateFormat') as label"), DB::raw('SUM(total) as value'), DB::raw('COUNT(*) as count'))
             ->groupBy('label')
             ->orderBy('label')
             ->get()
             ->map(function ($item) {
                 return [
                     'label' => $item->label,
                     'value' => (float) $item->value,
                     'count' => (int) $item->count,
                 ];
             });

        // 2. Status Distribution
        $statusData = $statusQuery
            ->select('status as label', DB::raw('SUM(total) as value'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucfirst($item->label),
                    'value' => (float) $item->value,
                    'count' => (int) $item->count,
                ];
            });

        // 3. Payment Method Distribution
        $paymentMethodData = $paymentMethodQuery
            ->whereNotNull('payment_method') // Only count where payment method is set
            ->select('payment_method as label', DB::raw('SUM(amount) as value'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucfirst($item->label),
                    'value' => (float) $item->value,
                    'count' => (int) $item->count,
                ];
            });
        // 4. Paid vs Unpaid Over Time
        // Paid
        $paidOverTimeData = $chartQuery->clone()
             ->where('status', 'paid')
             ->select(DB::raw("DATE_FORMAT(issue_date, '$dateFormat') as label"), DB::raw('SUM(total) as value'), DB::raw('COUNT(*) as count'))
             ->groupBy('label')
             ->orderBy('label')
             ->get()
             ->map(function ($item) {
                 return [
                     'label' => $item->label,
                     'value' => (float) $item->value,
                     'count' => (int) $item->count,
                 ];
             });

        // Unpaid (Everything not paid, e.g. unpaid, overdue)
        $unpaidOverTimeData = $chartQuery->clone()
             ->where('status', '!=', 'paid')
             ->select(DB::raw("DATE_FORMAT(issue_date, '$dateFormat') as label"), DB::raw('SUM(total) as value'), DB::raw('COUNT(*) as count'))
             ->groupBy('label')
             ->orderBy('label')
             ->get()
             ->map(function ($item) {
                 return [
                     'label' => $item->label,
                     'value' => (float) $item->value,
                     'count' => (int) $item->count,
                 ];
             });

        return [
            'summary' => $summary,
            'charts' => [
                'revenue_over_time' => $revenueData,
                'paid_over_time' => $paidOverTimeData,
                'unpaid_over_time' => $unpaidOverTimeData,
                'status_distribution' => $statusData,
                'payment_method_distribution' => $paymentMethodData,
            ],
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
            'filter_status' => $statuses,
            'filter_payment_method' => $paymentMethods,
            'chart_frequency' => $chartFrequency,
        ];
    }
}
