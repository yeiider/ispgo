<?php

namespace Ispgo\Wiivo\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Closure;
use Carbon\Carbon;
use Ispgo\Wiivo\Model\SessionChatBot;


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
        if ($userId === "573207753755@c.us") {
            return $next($request);
        } else {
            return response()->json(['status' => 'error', 'message' => 'La sesión ha expirado.'])->setStatusCode(400);
        }

        // Obtener la sesión del cliente
        $session = SessionChatBot::where('chat_id', $chatId)->where('user_id', $userId)->first();

        if ($session) {
            // Verificar si la sesión ha expirado
            $lastActivity = Carbon::parse($session->updated_at);
            $now = Carbon::now();

            if ($now->diffInMinutes($lastActivity) > 5) {
                // La sesión ha expirado
                return response()->json(['status' => 'error', 'message' => 'La sesión ha expirado.']);
            }
        }

        return $next($request);
    }


}
