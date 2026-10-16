<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessOltgoAlerts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * OLTGO avisa aquí las alertas de red apenas las detecta. La petición va
 * firmada con HMAC-SHA256 usando OLTGO_SECRET_KEY; lo demás se procesa en la cola.
 */
class OltgoWebhookController extends Controller
{
    private const MAX_SKEW = 300;

    public function __invoke(Request $request): JsonResponse
    {
        $secret = (string) config('oltgo.secret_key');
        $timestamp = (string) $request->header('X-Oltgo-Timestamp');
        $signature = (string) $request->header('X-Oltgo-Signature');
        $expected = 'sha256=' . hash_hmac('sha256', $timestamp . '.' . $request->getContent(), $secret);

        if ($secret === '' || !hash_equals($expected, $signature)) {
            return response()->json(['error' => 'firma inválida'], 401);
        }
        if (!ctype_digit($timestamp) || abs(time() - (int) $timestamp) > self::MAX_SKEW) {
            return response()->json(['error' => 'petición vencida'], 401);
        }

        $events = $request->json('events');
        if (!is_array($events)) {
            return response()->json(['error' => 'falta events'], 422);
        }
        if ($events) {
            ProcessOltgoAlerts::dispatch($events);
        }

        return response()->json(['received' => count($events)]);
    }
}
