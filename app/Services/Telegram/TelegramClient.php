<?php

namespace App\Services\Telegram;

use App\Settings\Telegram\SettingTelegram;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Cliente mínimo de la API de bots de Telegram.
 */
class TelegramClient
{
    public function __construct(private ?string $token = null)
    {
        $this->token ??= SettingTelegram::token();
    }

    private function call(string $method, array $params = []): array
    {
        if (!$this->token) {
            throw new RuntimeException('Falta el token del bot de Telegram en Configuración');
        }
        $response = Http::timeout(15)->asJson()->post("https://api.telegram.org/bot{$this->token}/{$method}", $params);
        $body = $response->json() ?? [];

        if (!($body['ok'] ?? false)) {
            $e = new TelegramException($body['description'] ?? "Telegram respondió {$response->status()}");
            $e->retryAfter = $body['parameters']['retry_after'] ?? null;
            throw $e;
        }

        return is_array($body['result']) ? $body['result'] : ['value' => $body['result']];
    }

    /** Datos del bot (nombre de usuario), para comprobar el token. */
    public function me(): array
    {
        return $this->call('getMe');
    }

    public function sendMessage(string $chatId, string $html): array
    {
        return $this->call('sendMessage', [
            'chat_id' => $chatId,
            'text' => $html,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ]);
    }

    /**
     * Chats que le escribieron al bot o donde lo agregaron, para encontrar su ID.
     *
     * @return array<int, array{id: string, title: string, type: string}>
     */
    public function recentChats(): array
    {
        $chats = [];
        foreach ($this->call('getUpdates', ['limit' => 100, 'allowed_updates' => ['message', 'my_chat_member', 'channel_post']]) as $u) {
            $chat = $u['message']['chat'] ?? $u['my_chat_member']['chat'] ?? $u['channel_post']['chat'] ?? null;
            if (!$chat) {
                continue;
            }
            $chats[$chat['id']] = [
                'id' => (string) $chat['id'],
                'title' => $chat['title'] ?? trim(($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? '')) ?: ($chat['username'] ?? ''),
                'type' => $chat['type'],
            ];
        }

        return array_values($chats);
    }
}
