<?php

namespace App\GraphQL\Builders;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

class TicketBuilder
{
    public function index($root, array $args): Builder
    {
        $query = Ticket::query();

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
        } else {
            // If no search is provided, only show recent tickets (30 days)
            $query->recent();
        }

        return $query;
    }
}
