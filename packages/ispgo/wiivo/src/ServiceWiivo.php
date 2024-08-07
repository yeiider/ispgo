<?php

namespace Ispgo\Wiivo;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class ServiceWiivo
{
    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function sendMessage($payload)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => WiivoConfigProvider::getApiKey()
        ])->post(WiivoConfigProvider::getUrlApi(), $payload);

        if ($response->failed()) {
            throw new \Exception('Error al enviar el mensaje: ' . $response->body());
        }

        return $response->json();
    }
}
