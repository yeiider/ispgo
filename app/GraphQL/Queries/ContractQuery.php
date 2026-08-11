<?php

namespace App\GraphQL\Queries;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Builder;

class ContractQuery
{
    /**
     * @param  null  $_
     * @param  array  $args
     * @return Builder
     */
    public function __invoke($_, array $args): Builder
    {
        $query = Contract::query();

        // Filter by customer name or identification
        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->whereHas('customer', function (Builder $cq) use ($search) {
                    $cq->where('first_name', 'like', '%' . $search . '%')
                       ->orWhere('last_name', 'like', '%' . $search . '%')
                       ->orWhere('identity_document', 'like', '%' . $search . '%');
                });
            });
        }

        if (!empty($args['status'])) {
            $query->where('status', $args['status']);
        }

        if (!empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        // Sorting
        $sortColumn = $args['sort_column'] ?? 'created_at';
        $sortDirection = isset($args['sort_direction']) && strtolower($args['sort_direction']) === 'asc' ? 'asc' : 'desc';

        $allowedSortColumns = ['id', 'start_date', 'end_date', 'status', 'signed_at', 'created_at'];
        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
