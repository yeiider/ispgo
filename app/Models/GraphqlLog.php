<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class GraphqlLog extends Model
{
    use MassPrunable;

    public const UPDATED_AT = null;

    protected $table = 'graphql_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'operation_type',
        'operation_name',
        'field',
        'action',
        'entity',
        'target_id',
        'summary',
        'details',
        'status',
        'error',
        'ip_address',
        'duration_ms',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Los registros viven N días (GRAPHQL_LOG_RETENTION_DAYS, por defecto 4).
     * Se eliminan con `php artisan model:prune` (programado a diario).
     */
    public function prunable(): Builder
    {
        $days = (int) config('graphql_logs.retention_days', 4);

        return static::where('created_at', '<', now()->subDays($days));
    }
}
