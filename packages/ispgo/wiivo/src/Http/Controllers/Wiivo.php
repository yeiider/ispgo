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
            ['current_option' => null, 'message_history' => '', 'interaction_history' => []]
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
            $response = $this->sendMessage($menuPayload);
            return response()->json(['status' => 'success', 'response' => $response]);

        }


        // Identificar la opción seleccionada por el cliente
        $selectedOption = trim($body);
        if (!$session->current_option) {
            $session->current_option = $selectedOption;
        }
        // Actualizar la sesión con la opción seleccionada
        $session->message_history .= "\n" . $body;

        // Actualizar el historial de interacciones
        $interactionHistory = $session->interaction_history ?? [];
        $interactionHistory[] = [
            'timestamp' => now()->toDateTimeString(),
            'message' => $body,
            'option' => count($interactionHistory)+1
        ];
        $session->interaction_history = $interactionHistory;

        // Actualizar la marca de tiempo de la sesión
        $session->touch();

        $session->save();

        try {
            $processor = ProcessChatFactory::getProcessor($session->current_option);
            $response = $processor->processMessage($body,$interactionHistory);
            $messagePayload = $response;
            $messagePayload['phone'] = $phone;
            // Enviar respuesta al cliente

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
    private function sendMessage($payload)
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
