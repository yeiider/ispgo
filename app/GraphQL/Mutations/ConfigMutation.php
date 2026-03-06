<?php

namespace App\GraphQL\Mutations;

use App\Services\Config\ConfigService;

class ConfigMutation
{
    protected ConfigService $service;

    public function __construct()
    {
        $this->service = new ConfigService();
    }

    /**
     * Upsert configuration values for a given scope.
     * Args: scope_id (Int), items ([{path, value}])
     */
    public function upsert($_, array $args)
    {
        $scopeId = (int)($args['scope_id'] ?? 0);
        $items = $args['items'] ?? [];
        return $this->service->upsertValues($items, $scopeId);
    }
}
