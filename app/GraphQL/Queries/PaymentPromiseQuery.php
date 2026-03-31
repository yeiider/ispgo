<?php

namespace App\GraphQL\Queries;

use App\Models\Invoice\PaymentPromise;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PaymentPromiseQuery
{
    /**
     * @param mixed $root
     * @param array $args
     * @return Builder
     */
    public function __invoke($root, array $args): Builder
    {
        $query = PaymentPromise::query();

        // Filter by ID
        if (!empty($args['id'])) {
            $query->where('id', $args['id']);
        }

        // Filter by invoice_id
        if (!empty($args['invoice_id'])) {
            $query->where('invoice_id', $args['invoice_id']);
        }

        // Filter by status
        if (!empty($args['status'])) {
            $query->where('status', $args['status']);
        }

        // Filter by customer_id
        if (!empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        // Filter by user_id (who created it)
        if (!empty($args['user_id'])) {
            $query->where('user_id', $args['user_id']);
        }

        // Filter by customer name
        if (!empty($args['customer_name'])) {
            $query->whereHas('customer', function ($q) use ($args) {
                $name = $args['customer_name'];
                $q->where('first_name', 'LIKE', "%{$name}%")
                    ->orWhere('last_name', 'LIKE', "%{$name}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$name}%");
            });
        }

        // Filter by promise date range
        if (!empty($args['promise_date_from'])) {
            $query->where('promise_date', '>=', $args['promise_date_from']);
        }

        if (!empty($args['promise_date_to'])) {
            $query->where('promise_date', '<=', $args['promise_date_to']);
        }

        // Apply sorting
        $sortColumn = $args['sort_column'] ?? 'promise_date';
        $sortDirection = isset($args['sort_direction']) && strtolower($args['sort_direction']) === 'asc' ? 'asc' : 'desc';

        $allowedSortColumns = ['id', 'promise_date', 'amount', 'status', 'created_at'];
        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            // Default sorting by promise_date
            $query->orderBy('promise_date', 'asc');
        }

        return $query;
    }
}
