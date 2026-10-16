<?php

namespace App\Services\Oltgo;

use App\Settings\Oltgo\ProviderOltgo;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;

/**
 * Cliente HTTP hacia OLTGO con cada petición firmada con OLTGO_SECRET_KEY
 * (HMAC-SHA256 de timestamp, método, ruta con query y cuerpo).
 */
class OltgoHttp
{
    public static function make(?int $timeout = null): PendingRequest
    {
        $secret = (string) config('oltgo.secret_key');

        return Http::baseUrl(ProviderOltgo::url())
            ->timeout($timeout ?? ProviderOltgo::timeout())
            ->acceptJson()
            ->withRequestMiddleware(function (RequestInterface $request) use ($secret) {
                $ts = (string) time();
                $uri = $request->getUri();
                $target = $uri->getPath() . ($uri->getQuery() !== '' ? '?' . $uri->getQuery() : '');
                $body = (string) $request->getBody();
                $signature = hash_hmac('sha256', "{$ts}.{$request->getMethod()} {$target}.{$body}", $secret);

                return $request
                    ->withHeader('X-Oltgo-Timestamp', $ts)
                    ->withHeader('X-Oltgo-Signature', 'sha256=' . $signature);
            });
    }
}
