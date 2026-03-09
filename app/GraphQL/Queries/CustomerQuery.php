<?php

namespace App\GraphQL\Queries;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): Builder
    {
        $query = Customer::query();

        if (!empty($args['first_name'])) {
            $query->where('first_name', 'like', '%' . $args['first_name'] . '%');
        }

        if (!empty($args['last_name'])) {
            $query->where('last_name', 'like', '%' . $args['last_name'] . '%');
        }

        if (!empty($args['identity_document'])) {
            $query->where('identity_document', 'like', '%' . $args['identity_document'] . '%');
        }

        if (!empty($args['email_address'])) {
            $query->where('email_address', 'like', '%' . $args['email_address'] . '%');
        }

        if (!empty($args['customer_status'])) {
            $query->where('customer_status', $args['customer_status']);
        }

        if (!empty($args['search'])) {
            $query->search($args['search']);
        }

        // Apply sorting
        $sortColumn = $args['sort_column'] ?? 'id';
        $sortDirection = isset($args['sort_direction']) && strtolower($args['sort_direction']) === 'asc' ? 'asc' : 'desc';

        $allowedSortColumns = ['id', 'first_name', 'last_name', 'created_at', 'identity_document', 'customer_status'];
        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }
}
