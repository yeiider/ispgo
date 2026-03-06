<?php

namespace App\GraphQL\Queries;

use App\Models\Customers\Address;

class GlobalCustomerAddressesQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Address::withoutGlobalScope('router_filter')
            ->where('customer_id', $args['customer_id'])
            ->get();
    }
}
