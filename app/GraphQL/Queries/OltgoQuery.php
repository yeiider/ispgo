<?php

namespace App\GraphQL\Queries;

use App\Models\Olt;
use App\Services\Oltgo\OltgoClient;
use App\Settings\Oltgo\ProviderOltgo;
use App\Support\SmartOltGate;
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

    /**
     * Proveedor de OLT para una zona. Con router_id: lo que usa esa zona (su
     * configuración o, si no tiene, la de por defecto). Sin router_id: lo que
     * se usa en alguna zona, para decidir el menú.
     */
    public function providers($root, array $args): array
    {
        $routerId = !empty($args['router_id']) ? (int) $args['router_id'] : null;
        $hasOlts = Olt::where('is_active', true)->exists();

        if ($routerId) {
            return [
                'smartolt' => SmartOltGate::enabled($routerId),
                'oltgo' => $hasOlts && ProviderOltgo::enabled($routerId),
            ];
        }

        return [
            'smartolt' => SmartOltGate::enabledAnywhere(),
            'oltgo' => $hasOlts && ProviderOltgo::enabled(),
        ];
    }

    /**
     * Busca la ONU de un servicio por serial; null si ninguna OLT la tiene.
     */
    public function findOnu($root, array $args): ?array
    {
        try {
            return $this->client->findOnu($args['serial']);
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'no encontrada')) {
                return null;
            }
            Log::warning('OLTGO: ' . $e->getMessage());
            throw new Error('No se pudo consultar OLTGO: ' . $e->getMessage());
        }
    }

    public function health($root, array $args): array
    {
        return $this->client->health();
    }

    public function status($root, array $args): array
    {
        return $this->call(fn () => $this->client->olts());
    }

    public function summary($root, array $args): array
    {
        $sum = $this->call(fn () => $this->client->summary((int) $args['olt_id'], (bool) ($args['refresh'] ?? false)));
        $sum['by_state'] = collect($sum['by_state'] ?? [])
            ->map(fn ($count, $state) => ['state' => $state, 'count' => $count])
            ->values()
            ->all();
        $sum['ports'] ??= [];

        return $sum;
    }

    public function system($root, array $args): array
    {
        return $this->call(fn () => $this->client->system((int) $args['olt_id']));
    }

    public function cards($root, array $args): array
    {
        return $this->call(fn () => $this->client->cards((int) $args['olt_id'], (bool) ($args['refresh'] ?? false)));
    }

    public function interfaces($root, array $args): array
    {
        $list = $this->call(fn () => $this->client->interfaces((int) $args['olt_id'], (bool) ($args['refresh'] ?? false)));

        return array_map(function (array $i) {
            $i['index'] = (string) $i['index'];
            $i['in_octets'] = (string) ($i['in_octets'] ?? 0);
            $i['out_octets'] = (string) ($i['out_octets'] ?? 0);

            return $i;
        }, $list);
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
