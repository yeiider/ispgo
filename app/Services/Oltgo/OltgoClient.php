<?php

namespace App\Services\Oltgo;

use App\Settings\Oltgo\ProviderOltgo;
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
        if (!ProviderOltgo::enabled()) {
            throw new RuntimeException('OLTGO está deshabilitado en Configuración');
        }

        return Http::baseUrl(ProviderOltgo::url())
            ->timeout(ProviderOltgo::timeout())
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

    /**
     * Estado de la conexión con OLTGO, para mostrar en el panel.
     */
    public function health(): array
    {
        $url = ProviderOltgo::url();
        try {
            $ok = $this->http()->timeout(5)->get('/health')->successful();

            return ['enabled' => true, 'url' => $url, 'reachable' => $ok, 'error' => $ok ? null : 'OLTGO no respondió bien'];
        } catch (\Throwable $e) {
            return ['enabled' => ProviderOltgo::enabled(), 'url' => $url, 'reachable' => false, 'error' => $e->getMessage()];
        }
    }

    public function olts(): array
    {
        return $this->get('/api/olts');
    }

    public function system(int $oltId): array
    {
        return $this->get("/api/olts/{$oltId}");
    }

    public function summary(int $oltId, bool $refresh = false): array
    {
        return $this->get("/api/olts/{$oltId}/summary", ['refresh' => $refresh ? 1 : null]);
    }

    public function findOnu(string $serial): array
    {
        return $this->get('/api/onus/' . rawurlencode($serial));
    }

    public function cards(int $oltId, bool $refresh = false): array
    {
        return $this->get("/api/olts/{$oltId}/cards", ['refresh' => $refresh ? 1 : null]);
    }

    public function interfaces(int $oltId, bool $refresh = false): array
    {
        return $this->get("/api/olts/{$oltId}/interfaces", ['refresh' => $refresh ? 1 : null]);
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
