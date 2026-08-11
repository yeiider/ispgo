<?php

namespace App\GraphQL\Queries;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Builder;

/**
 * Query to search ALL customers in the system, bypassing the router_filter global scope.
 *
 * This is needed when creating a service, where the operator must be able to find
 * ANY customer in the system regardless of which router zone they were originally
 * registered in. For example, a customer registered in "Cali" should be findable
 * by an operator in "Puerto Tejada" who wants to create a new service for them.
 *
 * Usage: allCustomers(search: "juan") { id first_name last_name ... }
 */
class AllCustomersQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): Builder
    {
        // Bypass the router_filter global scope intentionally.
        // This allows searching all customers in the system.
        $query = Customer::withoutGlobalScope('router_filter');

        // Search by name, identity document or email
        if (!empty($args['search'])) {
            $query->search($args['search']);
        }

        // Optional: filter by status
        if (!empty($args['customer_status'])) {
            $query->where('customer_status', $args['customer_status']);
        }

        return $query->orderBy('first_name');
    }
}
