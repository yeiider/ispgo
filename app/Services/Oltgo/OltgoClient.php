<?php

namespace App\Services\Oltgo;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Cliente HTTP de OLTGO. ISPGO nunca habla SNMP: le pregunta a OLTGO.
 */
class OltgoClient
{
    private function http(): PendingRequest
    {
        return Http::baseUrl(rtrim((string) config('oltgo.url'), '/'))
            ->timeout((int) config('oltgo.timeout', 20))
            ->acceptJson();
    }

    private function get(string $path, array $query = []): mixed
    {
        $response = $this->http()->get($path, array_filter($query, fn ($v) => $v !== null && $v !== ''));

        if ($response->failed()) {
            throw new RuntimeException($response->json('error') ?? "OLTGO respondió {$response->status()}");
        }

        return $response->json();
    }

    public function olts(): array
    {
        return $this->get('/api/olts');
    }

    public function system(int $oltId): array
    {
        return $this->get("/api/olts/{$oltId}");
    }

    public function summary(int $oltId): array
    {
        return $this->get("/api/olts/{$oltId}/summary");
    }

    public function ports(int $oltId): array
    {
        return $this->get("/api/olts/{$oltId}/ports");
    }

    public function onus(int $oltId, ?string $port = null, ?string $state = null): array
    {
        return $this->get("/api/olts/{$oltId}/onus", ['port' => $port, 'state' => $state]);
    }

    public function onu(int $oltId, string $serial): array
    {
        return $this->get("/api/olts/{$oltId}/onus/" . rawurlencode($serial));
    }

    public function tunnels(): array
    {
        return $this->get('/api/tunnels');
    }

    public function reload(): void
    {
        $this->http()->post('/internal/reload');
    }
}
