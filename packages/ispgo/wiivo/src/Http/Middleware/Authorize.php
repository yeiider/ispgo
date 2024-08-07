<?php

namespace Ispgo\Wiivo\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Closure;
use Carbon\Carbon;
use Ispgo\Wiivo\Model\SessionChatBot;
use Ispgo\Wiivo\WiivoConfigProvider;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param \Closure(Request):mixed $next
     * @return JsonResponse
     */
    public function handle($request, Closure $next)
    {
        $chatId = $request->input('data.chat.id');
        $userId = $request->input('data.from');

        $environment = WiivoConfigProvider::getEnv();
        $chatsId = WiivoConfigProvider::getTelephoneTest();

        // Verificar si el entorno es sandbox
        if ($environment === "sandbox") {
            // Permitir solo los chatIds que están en el array de chatsId
            if (!in_array($chatId, $chatsId)) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
        }

        $session = SessionChatBot::where('chat_id', $chatId)->where('user_id', $userId)->first();

        if ($session) {
            // Verificar si la sesión ha expirado
            $lastActivity = Carbon::parse($session->updated_at);
            $now = Carbon::now();

            if ($now->diffInMinutes($lastActivity) > 5) {
                // La sesión ha expirado
                $session->delete();
            }
        }

        return $next($request);
    }
}
