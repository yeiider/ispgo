<?php

namespace App\GraphQL\Builders;

use App\Models\TicketIssueType;
use Illuminate\Database\Eloquent\Builder;

class TicketIssueTypeBuilder
{
    public function index($root, array $args): Builder
    {
        $query = TicketIssueType::query();

        if (!empty($args['search'])) {
            $search = '%' . $args['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        if (isset($args['is_active'])) {
            $query->where('is_active', $args['is_active']);
        }

        return $query;
    }
}
