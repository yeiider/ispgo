<?php

namespace App\GraphQL\Queries;

use App\Models\GraphqlLog;
use Illuminate\Database\Eloquent\Builder;

class GraphqlLogQuery
{
    public function paginate($root, array $args): Builder
    {
        $query = GraphqlLog::query();

        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('summary', 'like', "%{$search}%")
                    ->orWhere('field', 'like', "%{$search}%")
                    ->orWhere('entity', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        foreach (['user_id', 'action', 'operation_type', 'field', 'entity', 'target_id', 'status'] as $column) {
            if (!empty($args[$column])) {
                $query->where($column, $args[$column]);
            }
        }

        if (!empty($args['actions'])) {
            $query->whereIn('action', $args['actions']);
        }

        if (!empty($args['date_from'])) {
            $query->where('created_at', '>=', $args['date_from']->copy()->startOfDay());
        }

        if (!empty($args['date_to'])) {
            $query->where('created_at', '<=', $args['date_to']->copy()->endOfDay());
        }

        $direction = strtolower($args['sort_direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy('id', $direction);
    }

    public function users(): array
    {
        return GraphqlLog::query()
            ->select('user_id as id', 'user_name as name')
            ->whereNotNull('user_id')
            ->distinct()
            ->orderBy('user_name')
            ->get()
            ->toArray();
    }

    public function fields($root, array $args): array
    {
        return GraphqlLog::query()
            ->when(!empty($args['action']), fn (Builder $q) => $q->where('action', $args['action']))
            ->distinct()
            ->orderBy('field')
            ->pluck('field')
            ->all();
    }
}
