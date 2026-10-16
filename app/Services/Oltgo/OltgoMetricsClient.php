<?php

namespace App\Services\Oltgo;

use App\Settings\Oltgo\ProviderOltgo;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Históricos e informes de OLTGO (TimescaleDB). Los rangos van como
 * range (6h, 24h, 7d, 30d...) o from/to en ISO 8601.
 */
class OltgoMetricsClient
{
    private function get(string $path, array $query = []): mixed
    {
        if (!ProviderOltgo::enabled()) {
            throw new RuntimeException('OLTGO está deshabilitado en Configuración');
        }
        $query = array_filter($query, fn ($v) => $v !== null && $v !== '' && $v !== []);
        $response = Http::baseUrl(ProviderOltgo::url())
            ->timeout(max(ProviderOltgo::timeout(), 60))
            ->acceptJson()
            ->get($path, $query);

        if ($response->failed()) {
            throw new RuntimeException($response->json('error') ?? "OLTGO respondió {$response->status()}");
        }

        return $response->json();
    }

    private static function range(?array $range): array
    {
        return [
            'range' => $range['range'] ?? null,
            'from' => $range['from'] ?? null,
            'to' => $range['to'] ?? null,
        ];
    }

    public function traffic(int $oltId, ?array $range, ?string $kind, array $interfaces, bool $total): array
    {
        return $this->get("/api/olts/{$oltId}/metrics/traffic", self::range($range) + [
            'kind' => $kind,
            'interfaces' => implode(',', $interfaces),
            'total' => $total ? 1 : null,
        ]);
    }

    public function trafficStats(int $oltId, ?array $range, ?string $kind): array
    {
        return $this->get("/api/olts/{$oltId}/metrics/traffic/stats", self::range($range) + ['kind' => $kind]);
    }

    public function trafficProfile(int $oltId, ?array $range, ?string $kind): array
    {
        return $this->get("/api/olts/{$oltId}/metrics/traffic/profile", self::range($range) + ['kind' => $kind]);
    }

    public function health(int $oltId, ?array $range): array
    {
        return $this->get("/api/olts/{$oltId}/metrics/health", self::range($range));
    }

    public function onuStats(int $oltId, ?array $range, ?string $port): array
    {
        return $this->get("/api/olts/{$oltId}/metrics/onus", self::range($range) + ['port' => $port]);
    }

    public function onuRanking(int $oltId, ?array $range, string $by, ?int $limit): array
    {
        return $this->get("/api/olts/{$oltId}/metrics/onus/ranking", self::range($range) + ['by' => $by, 'limit' => $limit]);
    }

    public function events(int $oltId, ?array $range, array $kinds, ?string $port, ?string $serial, ?int $limit): array
    {
        return $this->get("/api/olts/{$oltId}/events", self::range($range) + [
            'kind' => implode(',', $kinds),
            'port' => $port,
            'serial' => $serial,
            'limit' => $limit,
        ]);
    }

    public function report(int $oltId, ?array $range): array
    {
        return $this->get("/api/olts/{$oltId}/report", self::range($range));
    }

    public function onuHistory(string $serial, ?array $range): array
    {
        return $this->get('/api/onus/' . rawurlencode($serial) . '/history', self::range($range));
    }

    public function onuTraffic(string $serial, ?array $range): array
    {
        return $this->get('/api/onus/' . rawurlencode($serial) . '/traffic', self::range($range));
    }

    public function onuTrafficLive(string $serial): array
    {
        return $this->get('/api/onus/' . rawurlencode($serial) . '/traffic/live');
    }

    public function diagnosis(string $serial): array
    {
        return $this->get('/api/onus/' . rawurlencode($serial) . '/diagnosis');
    }

    /**
     * Eventos de todas las OLT posteriores a $since, del más viejo al más nuevo.
     */
    public function eventsSince(string $since, array $kinds, int $limit = 500): array
    {
        return $this->get('/api/events', ['since' => $since, 'kind' => implode(',', $kinds), 'limit' => $limit]);
    }
}
