<?php

namespace App\GraphQL\Queries;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Builder;

class ServiceQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): Builder
    {
        $query = Service::query();

        // Generic search: SN or customer name (first_name + last_name)
        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('sn', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function (Builder $cq) use ($search) {
                      $cq->search($search);
                  });
            });
        }

        if (!empty($args['service_ip'])) {
            $query->where('service_ip', 'like', '%' . $args['service_ip'] . '%');
        }

        if (!empty($args['service_status'])) {
            $query->where('service_status', $args['service_status']);
        }

        if (!empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        if (!empty($args['mac_address'])) {
            $query->where('mac_address', 'like', '%' . $args['mac_address'] . '%');
        }

        if (!empty($args['service_type'])) {
            $query->where('service_type', $args['service_type']);
        }

        if (!empty($args['sn'])) {
            $query->where('sn', 'like', '%' . $args['sn'] . '%');
        }

        // Apply sorting
        $sortColumn = $args['sort_column'] ?? 'id';
        $sortDirection = isset($args['sort_direction']) && strtolower($args['sort_direction']) === 'asc' ? 'asc' : 'desc';

        $allowedSortColumns = ['id', 'service_ip', 'service_status', 'mac_address', 'service_type', 'sn', 'created_at'];
        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }
}
