<?php

namespace App\Jobs;

use App\Services\Telegram\TelegramClient;
use App\Services\Telegram\TelegramException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Envía un aviso de sistema a un chat de Telegram desde la cola.
 */
class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(public string $chatId, public string $html)
    {
    }

    public function backoff(): array
    {
        return [10, 30, 60, 120];
    }

    public function handle(): void
    {
        try {
            app(TelegramClient::class)->sendMessage($this->chatId, $this->html);
        } catch (TelegramException $e) {
            // Límite de envío: se reintenta cuando Telegram diga.
            if ($e->retryAfter) {
                $this->release($e->retryAfter);

                return;
            }
            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::warning("Telegram: no se pudo enviar al chat {$this->chatId}: {$e->getMessage()}");
    }
}
