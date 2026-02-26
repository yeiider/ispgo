<?php

namespace App\GraphQL\Queries;

use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Builder;

class InvoiceQuery
{
    /**
     * Custom resolver for invoices query with proper null handling.
     *
     * @param mixed $root
     * @param array $args
     * @return Builder
     */
    public function __invoke($root, array $args): Builder
    {
        $query = Invoice::query();

        // Apply increment_id filter if provided
        if (!empty($args['increment_id'])) {
            $query->where('increment_id', 'like', '%' . $args['increment_id'] . '%');
        }

        // Apply status filter if provided
        if (!empty($args['status'])) {
            $query->where('status', $args['status']);
        }

        // Apply customer_id filter if provided
        if (!empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        // Apply router_id filter if provided
        if (!empty($args['router_id'])) {
            $query->where('router_id', $args['router_id']);
        }

        // Apply created_at_from filter if provided
        if (!empty($args['created_at_from'])) {
            $query->where('created_at', '>=', $args['created_at_from']);
        }

        // Apply created_at_to filter if provided
        if (!empty($args['created_at_to'])) {
            $query->where('created_at', '<=', $args['created_at_to']);
        }

        // Apply customer_name filter if provided
        if (!empty($args['customer_name'])) {
            $query->whereHas('customer', function ($q) use ($args) {
                $name = $args['customer_name'];
                $q->where('first_name', 'LIKE', "%{$name}%")
                    ->orWhere('last_name', 'LIKE', "%{$name}%")
                    ->orWhere(\Illuminate\Support\Facades\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$name}%");
            });
        }

        // Apply payment_method filter directly on invoices table
        if (!empty($args['payment_method'])) {
            $query->where('payment_method', $args['payment_method']);
        }

        // Apply payment_date_from filter using additional_information->finalized_at (JSON)
        if (!empty($args['payment_date_from'])) {
            $from = $args['payment_date_from']; // formato: Y-m-d
            $query->whereRaw(
                "JSON_UNQUOTE(JSON_EXTRACT(additional_information, '$.finalized_at')) >= ?",
                [$from]
            );
        }

        // Apply payment_date_to filter using additional_information->finalized_at (JSON)
        if (!empty($args['payment_date_to'])) {
            $to = $args['payment_date_to']; // formato: Y-m-d
            $query->whereRaw(
                "JSON_UNQUOTE(JSON_EXTRACT(additional_information, '$.finalized_at')) <= ?",
                [$to . 'T23:59:59']
            );
        }

        return $query;
    }
}
