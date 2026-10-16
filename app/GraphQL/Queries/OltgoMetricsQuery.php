<?php

namespace App\GraphQL\Queries;

use App\Services\Oltgo\OltgoMetricsClient;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Históricos e informes de las OLT, guardados por OLTGO.
 */
class OltgoMetricsQuery
{
    public function __construct(private OltgoMetricsClient $client)
    {
    }

    public function traffic($root, array $args): array
    {
        return $this->call(fn () => $this->client->traffic(
            (int) $args['olt_id'],
            $args['range'] ?? null,
            $args['kind'] ?? null,
            $args['interfaces'] ?? [],
            (bool) ($args['total'] ?? false),
        ));
    }

    public function trafficStats($root, array $args): array
    {
        return $this->call(fn () => $this->client->trafficStats((int) $args['olt_id'], $args['range'] ?? null, $args['kind'] ?? null));
    }

    public function trafficProfile($root, array $args): array
    {
        return $this->call(fn () => $this->client->trafficProfile((int) $args['olt_id'], $args['range'] ?? null, $args['kind'] ?? null));
    }

    public function health($root, array $args): array
    {
        return $this->call(fn () => $this->client->health((int) $args['olt_id'], $args['range'] ?? null));
    }

    public function onuStats($root, array $args): array
    {
        return $this->call(fn () => $this->client->onuStats((int) $args['olt_id'], $args['range'] ?? null, $args['port'] ?? null));
    }

    public function onuRanking($root, array $args): array
    {
        return $this->call(fn () => $this->client->onuRanking(
            (int) $args['olt_id'],
            $args['range'] ?? null,
            $args['by'] ?? 'drops',
            $args['limit'] ?? null,
        ));
    }

    public function events($root, array $args): array
    {
        return $this->call(fn () => $this->client->events(
            (int) $args['olt_id'],
            $args['range'] ?? null,
            $args['kind'] ?? [],
            $args['port'] ?? null,
            $args['serial'] ?? null,
            $args['limit'] ?? null,
        ));
    }

    public function report($root, array $args): array
    {
        $report = $this->call(fn () => $this->client->report((int) $args['olt_id'], $args['range'] ?? null));
        $report['event_counts'] = collect($report['event_counts'] ?? [])
            ->map(fn ($count, $kind) => ['kind' => $kind, 'count' => $count])
            ->sortByDesc('count')
            ->values()
            ->all();

        return $report;
    }

    public function onuHistory($root, array $args): ?array
    {
        try {
            return $this->client->onuHistory($args['serial'], $args['range'] ?? null);
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'no encontrada')) {
                return null;
            }
            throw new Error('No se pudo consultar OLTGO: ' . $e->getMessage());
        }
    }

    private function call(callable $fn): mixed
    {
        try {
            return $fn();
        } catch (Throwable $e) {
            Log::warning('OLTGO históricos: ' . $e->getMessage());
            throw new Error('No se pudo consultar los históricos de OLTGO: ' . $e->getMessage());
        }
    }
}
