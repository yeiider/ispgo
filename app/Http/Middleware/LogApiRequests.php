<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiRequestLog;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        try {
            ApiRequestLog::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'query_params' => $request->query(),
                'request_body' => $request->except(['password', 'password_confirmation']),
                'response_status' => $response->getStatusCode(),
                'response_body' => json_decode($response->getContent(), true) ?: $response->getContent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if log cannot be created, to not disrupt the application
            \Illuminate\Support\Facades\Log::error('Failed to log API request: ' . $e->getMessage());
        }
    }
}
