<?php

namespace App\Http\Middleware;

use App\GraphQL\Logging\GraphqlLogBuilder;
use App\GraphQL\Logging\GraphqlLogCollector;
use App\Models\GraphqlLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Registra en graphql_logs cada consulta (view) y mutación (create/update/delete/other)
 * que pasa por /graphql, con el usuario que la hizo y un resumen corto de lo que pidió o cambió.
 */
class LogGraphQLRequests
{
    public function __construct(private GraphqlLogCollector $collector)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (config('graphql_logs.enabled', true)) {
            $this->collector->start();
            $request->attributes->set('graphql_log_started_at', microtime(true));
        }

        return $next($request);
    }

    /**
     * Se ejecuta después de enviar la respuesta, para no sumar tiempo a la petición.
     */
    public function terminate(Request $request, Response $response): void
    {
        $startedAt = $request->attributes->get('graphql_log_started_at');
        if ($startedAt === null) {
            return;
        }

        try {
            $user = $request->user();
            $error = $this->extractError($response);
            $changes = app(GraphqlLogCollector::class)->changes();
            $builder = new GraphqlLogBuilder();

            $base = [
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'Sistema',
                'status' => $error === null ? 'success' : 'error',
                'error' => $error,
                'ip_address' => $request->ip(),
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            ];

            foreach ($this->operations($request) as $payload) {
                foreach ($builder->build($payload, $changes) as $row) {
                    GraphqlLog::create($base + $row);
                }
            }
        } catch (\Throwable $e) {
            Log::error('No se pudo registrar la petición GraphQL: ' . $e->getMessage());
        }
    }

    /**
     * Soporta peticiones simples, en lote (array) y multipart (subida de archivos).
     *
     * @return array<int, array>
     */
    private function operations(Request $request): array
    {
        $input = $request->input();

        if ($request->has('operations')) {
            $decoded = json_decode((string) $request->input('operations'), true);
            $input = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($input) || $input === []) {
            return [];
        }

        return array_is_list($input) ? array_filter($input, 'is_array') : [$input];
    }

    private function extractError(Response $response): ?string
    {
        if ($response->getStatusCode() >= 400) {
            return 'HTTP ' . $response->getStatusCode();
        }

        $content = (string) $response->getContent();
        if (!str_starts_with(ltrim($content), '{"errors"')) {
            return null;
        }

        if (preg_match('/"message"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/', substr($content, 0, 4000), $m)) {
            return mb_substr(json_decode('"' . $m[1] . '"') ?? $m[1], 0, 250);
        }

        return 'Error';
    }
}
