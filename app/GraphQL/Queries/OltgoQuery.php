<?php

namespace App\GraphQL\Queries;

use App\Services\Oltgo\OltgoClient;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Datos en vivo de las OLT. ISPGO no habla SNMP: se los pide a OLTGO.
 */
class OltgoQuery
{
    public function __construct(private OltgoClient $client)
    {
    }

    public function status($root, array $args): array
    {
        return $this->call(fn () => $this->client->olts());
    }

    public function summary($root, array $args): array
    {
        $sum = $this->call(fn () => $this->client->summary((int) $args['olt_id']));
        $sum['by_state'] = collect($sum['by_state'] ?? [])
            ->map(fn ($count, $state) => ['state' => $state, 'count' => $count])
            ->values()
            ->all();
        $sum['ports'] ??= [];

        return $sum;
    }

    public function ports($root, array $args): array
    {
        return $this->call(fn () => $this->client->ports((int) $args['olt_id']));
    }

    public function onus($root, array $args): array
    {
        return $this->call(fn () => $this->client->onus(
            (int) $args['olt_id'],
            $args['port'] ?? null,
            $args['state'] ?? null,
        ));
    }

    public function onu($root, array $args): ?array
    {
        return $this->call(fn () => $this->client->onu((int) $args['olt_id'], $args['serial']));
    }

    private function call(callable $fn): mixed
    {
        try {
            return $fn();
        } catch (Throwable $e) {
            Log::warning('OLTGO: ' . $e->getMessage());
            throw new Error('No se pudo consultar OLTGO: ' . $e->getMessage());
        }
    }
}
