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

        if (!empty($args['name'])) {
            $name = $args['name'];
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', '%' . $name . '%')
                  ->orWhere('last_name', 'like', '%' . $name . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $name . '%']);
            });
        }

        if (isset($args['has_services']) && $args['has_services'] !== 'all') {
            if ($args['has_services'] === 'has_services') {
                $query->has('services');
            } elseif ($args['has_services'] === 'no_services') {
                $query->doesntHave('services');
            }
        }

        if (!empty($args['service_status']) && $args['service_status'] !== 'all') {
            $query->whereHas('services', function ($q) use ($args) {
                $q->where('service_status', $args['service_status']);
            });
        }

        if (isset($args['billing_status']) && $args['billing_status'] !== 'all') {
            if ($args['billing_status'] === 'pending') {
                $query->whereHas('invoices', function ($q) {
                    $q->where('status', 'unpaid');
                });
            } elseif ($args['billing_status'] === 'paid') {
                $query->whereDoesntHave('invoices', function ($q) {
                    $q->where('status', 'unpaid');
                });
            }
        }

        if (!empty($args['created_at_date'])) {
            $query->whereDate('created_at', $args['created_at_date']);
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
