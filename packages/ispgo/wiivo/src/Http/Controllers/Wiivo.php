<?php

namespace Ispgo\Wiivo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Ispgo\Wiivo\Model\SessionChatBot;
use Ispgo\Wiivo\Process\ProcessChatFactory;
use Ispgo\Wiivo\WiivoConfigProvider;

class Wiivo extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function handleWebhook(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->input('data');

        $body = $data['body'];
        $chatId = $data['chat']['id'];
        $userId = $data['from'];
        $phone = $data['fromNumber'];

        // Obtener o crear una nueva sesión para el cliente
        $session = SessionChatBot::firstOrCreate(
            ['chat_id' => $chatId, 'user_id' => $userId],
            ['current_option' => null, 'message_history' => '', 'interaction_history' => json_encode([])]
        );

        if ($session->wasRecentlyCreated) {
            // Si la sesión es nueva, enviar el mensaje de bienvenida con el menú
            $welcomeMessage = WiivoConfigProvider::getWelcomeMessage();
            $menuPayload = [
                'phone' => $phone,
                'message' => trim($welcomeMessage),
                'buttons' => [
                    ['id' => 'inv', 'text' => 'Consultar factura'],
                    ['id' => 'pay', 'text' => 'Realizar pago'],
                    ['id' => 'tik', 'text' => 'Crear ticket']
                ]
            ];
            $this->sendMessage($menuPayload);
        }

        // Identificar la opción seleccionada por el cliente
        $selectedOption = substr($body, 0, 1);

        // Actualizar la sesión con la opción seleccionada
        $session->current_option = $selectedOption;
        $session->message_history .= "\n" . $body;

        // Actualizar el historial de interacciones
        $interactionHistory = json_decode($session->interaction_history, true);
        $interactionHistory[] = [
            'timestamp' => now()->toDateTimeString(),
            'message' => $body,
            'option' => $selectedOption
        ];
        $session->interaction_history = json_encode($interactionHistory);

        // Actualizar la marca de tiempo de la sesión
        $session->touch();

        $session->save();

        try {
            $processor = ProcessChatFactory::getProcessor($selectedOption);
            $response = $processor->processMessage($body);

            // Enviar respuesta al cliente
            $messagePayload = [
                'phone' => $phone,
                'message' => $response
            ];
            $this->sendMessage($messagePayload);

            return response()->json(['status' => 'success', 'response' => $response]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    private function sendMessage($payload): void
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => WiivoConfigProvider::getApiKey()
        ])->post(WiivoConfigProvider::getUrlApi(), $payload);

        if ($response->failed()) {
            throw new \Exception('Error al enviar el mensaje: ' . $response->body());
        }

        $response->json();
    }
}
