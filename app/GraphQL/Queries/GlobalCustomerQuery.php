<?php

namespace App\GraphQL\Queries;

use App\Models\Customers\Customer;

class GlobalCustomerQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // Bypass the router_filter global scope intentionally.
        // Eager load addresses also without the global scope.
        return Customer::withoutGlobalScope('router_filter')
            ->with(['addresses' => function ($query) {
                $query->withoutGlobalScope('router_filter');
            }])
            ->find($args['id']);
    }
}
