<?php

namespace App\GraphQL\Queries;

use App\Models\TicketIssueType;

class TicketIssueTypeQuery
{
    public function allActive($root, array $args): \Illuminate\Database\Eloquent\Collection
    {
        return TicketIssueType::where('is_active', true)->orderBy('name', 'asc')->get();
    }
}
