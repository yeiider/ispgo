<?php

namespace App\GraphQL\Queries;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogQuery
{
    public function paginate($root, array $args): Builder
    {
        $query = ActivityLog::query();

        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        if (!empty($args['user_id'])) {
            $query->where('user_id', $args['user_id']);
        }

        if (!empty($args['module'])) {
            $query->where('module', $args['module']);
        }

        if (!empty($args['action'])) {
            $query->where('action', $args['action']);
        }

        $sortColumn = $args['sort_column'] ?? 'created_at';
        $sortDirection = strtolower($args['sort_direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortColumn, $sortDirection);
    }
}
