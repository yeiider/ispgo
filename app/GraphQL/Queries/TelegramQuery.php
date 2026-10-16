<?php

namespace App\GraphQL\Queries;

use App\Services\Telegram\SystemNotifier;
use App\Services\Telegram\TelegramClient;
use App\Settings\Telegram\SettingTelegram;
use GraphQL\Error\Error;
use Throwable;

/**
 * Estado, descubrimiento de chats y prueba del bot de Telegram.
 */
class TelegramQuery
{
    public function status($root, array $args): array
    {
        $out = [
            'enabled' => SettingTelegram::enabled(),
            'has_token' => SettingTelegram::token() !== null,
            'bot_username' => null,
            'chat_ids' => SettingTelegram::chatIds(),
            'error' => null,
        ];
        if ($out['has_token']) {
            try {
                $out['bot_username'] = '@' . (app(TelegramClient::class)->me()['username'] ?? '');
            } catch (Throwable $e) {
                $out['error'] = 'Token inválido: ' . $e->getMessage();
            }
        }

        return $out;
    }

    public function recentChats($root, array $args): array
    {
        try {
            return app(TelegramClient::class)->recentChats();
        } catch (Throwable $e) {
            throw new Error('Telegram: ' . $e->getMessage());
        }
    }

    public function sendTest($root, array $args): array
    {
        $chats = !empty($args['chat_id']) ? [$args['chat_id']] : SettingTelegram::chatIds();
        if (!$chats) {
            return ['success' => false, 'message' => 'No hay chats configurados'];
        }
        $html = SystemNotifier::format('Prueba de ISPGO', 'Si ves este mensaje, los avisos de sistema llegan a este chat.', 'success');
        $failed = [];
        foreach ($chats as $chat) {
            try {
                app(TelegramClient::class)->sendMessage($chat, $html);
            } catch (Throwable $e) {
                $failed[] = "{$chat}: {$e->getMessage()}";
            }
        }

        return $failed
            ? ['success' => false, 'message' => 'No llegó a ' . implode('; ', $failed)]
            : ['success' => true, 'message' => 'Mensaje de prueba enviado a ' . count($chats) . ' chat(s)'];
    }
}
