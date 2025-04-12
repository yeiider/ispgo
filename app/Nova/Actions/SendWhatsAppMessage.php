<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\ActionFields;
use Ispgo\Wiivo\ServiceWiivo;

class SendWhatsAppMessage extends Action
{
    use InteractsWithQueue, Queueable;
    public $withoutActionEvents = true;

    public $name = 'Enviar mensaje WhatsApp';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $customer = $model->customer;

            if (!$customer || !$customer->phone_number) {
                return Action::danger("El cliente no tiene número de teléfono registrado.");
            }

            $phone = '57' . preg_replace('/\D/', '', $customer->phone_number);

            // Obtener nombre del cliente en mayúsculas si existe
            $nombre = strtoupper(optional($customer)->first_name ?? 'CLIENTE');

            // Reemplazar variable {{ nombre }} en el mensaje
            $message = str_replace('{{ nombre }}', $nombre, $fields->message);

            $payload = [
                'phone' => $phone,
                'message' => $message,
            ];

            try {
                $wiivo = new ServiceWiivo();
                $wiivo->sendMessage($payload);
            } catch (\Exception $e) {
                return Action::danger("Error al enviar mensaje: " . $e->getMessage());
            }
        }

        return Action::message('Mensaje(s) enviado(s) correctamente.');
    }

    public function fields(\Laravel\Nova\Http\Requests\NovaRequest $request): array
    {
        return [
            Textarea::make('Mensaje', 'message')
                ->rules('required', 'string', 'max:1000')
                ->help('Puedes usar la variable {{ nombre }} para insertar el primer nombre del cliente en mayúsculas.'),
        ];
    }
}
