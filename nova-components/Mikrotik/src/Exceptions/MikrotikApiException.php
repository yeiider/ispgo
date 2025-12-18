<?php

namespace Ispgo\Mikrotik\Exceptions;

use Exception;

/**
 * Excepción para errores de la API de Mikrotik
 */
class MikrotikApiException extends Exception
{
    protected int $httpCode;

    public function __construct(string $message, int $httpCode = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->httpCode = $httpCode;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
