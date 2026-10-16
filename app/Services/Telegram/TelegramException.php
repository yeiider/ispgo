<?php

namespace App\Services\Telegram;

use RuntimeException;

class TelegramException extends RuntimeException
{
    /** Segundos que Telegram pide esperar cuando se envían demasiados mensajes. */
    public ?int $retryAfter = null;
}
