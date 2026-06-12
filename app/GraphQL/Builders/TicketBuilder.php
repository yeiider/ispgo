<?php

namespace App\GraphQL\Builders;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

class TicketBuilder
{
    public function index($root, array $args): Builder
    {
        $query = Ticket::query();

        $recentOnly = $args['recent_only'] ?? true;

        if (isset($args['title']) && !empty($args['title'])) {
            // Frontend passes '%searchQuery%' when searching by title
            $search = trim($args['title'], '%'); 
            
            $query->where(function ($q) use ($search) {
                // Ignore 30 days limit and search by id, title, or customer name
                $q->where('id', $search)
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                  });
            });
        } elseif ($recentOnly) {
            // If recent_only is true (default), only show recent tickets (30 days)
            $query->recent();
        }

        if (isset($args['router_id']) && !empty($args['router_id'])) {
            $routerId = $args['router_id'];
            $query->where(function ($q) use ($routerId) {
                $q->whereHas('customer', function ($q2) use ($routerId) {
                    $q2->where('router_id', $routerId);
                })->orWhereHas('service', function ($q2) use ($routerId) {
                    $q2->where('router_id', $routerId);
                });
            });
        }

        if (isset($args['status']) && !empty($args['status']) && $args['status'] !== 'all') {
            $query->where('status', $args['status']);
        }

        if (isset($args['priority']) && !empty($args['priority']) && $args['priority'] !== 'all') {
            $query->where('priority', $args['priority']);
        }

        if (isset($args['customer_id']) && !empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        if (isset($args['service_id']) && !empty($args['service_id'])) {
            $query->where('service_id', $args['service_id']);
        }

        return $query;
    }
}
