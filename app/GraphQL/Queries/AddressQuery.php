<?php

namespace App\GraphQL\Queries;

use App\Models\Customers\Address;
use Illuminate\Database\Eloquent\Builder;

class AddressQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): Builder
    {
        $query = Address::query();

        if (!empty($args['address'])) {
            $query->where('address', 'like', '%' . $args['address'] . '%');
        }

        if (!empty($args['city'])) {
            $query->where('city', 'like', '%' . $args['city'] . '%');
        }

        if (!empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        // Apply sorting
        $sortColumn = $args['sort_column'] ?? 'id';
        $sortDirection = isset($args['sort_direction']) && strtolower($args['sort_direction']) === 'asc' ? 'asc' : 'desc';

        $allowedSortColumns = ['id', 'address', 'city', 'state_province', 'postal_code', 'country', 'created_at'];
        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }
}
