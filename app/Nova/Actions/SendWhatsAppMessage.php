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
        $errores = [];

        foreach ($models as $model) {
            $customer = $model->customer;

            if (!$customer || !$customer->phone_number) {
                $errores[] = "ID {$model->id}: El cliente no tiene número de teléfono.";
                continue;
            }

            $numeroOriginal = trim($customer->phone_number);

            // Ignorar números con "+" (probablemente internacionales)
            if (str_starts_with($numeroOriginal, '+')) {
                $errores[] = "ID {$model->id}: Número internacional no permitido ($numeroOriginal).";
                continue;
            }

            // Limpiar y agregar código país
            $phone = '57' . preg_replace('/\D/', '', $numeroOriginal);

            // Obtener nombre en mayúsculas
            $nombre = strtoupper(optional($customer)->first_name ?? 'CLIENTE');

            // Reemplazar variable {{ nombre }}
            $message = str_replace('{{ nombre }}', $nombre, $fields->message);

            $payload = [
                'phone' => $phone,
                'message' => $message,
            ];

            try {
                $wiivo = new ServiceWiivo();
                $wiivo->sendMessage($payload);
            } catch (\Throwable $e) {
                $errores[] = "ID {$model->id}: Error al enviar mensaje a $phone - {$e->getMessage()}";
                continue;
            }
        }

        if (!empty($errores)) {
            return Action::danger("Se enviaron algunos mensajes, pero ocurrieron errores:\n" . implode("\n", $errores));
        }

        return Action::message('Todos los mensajes se enviaron correctamente.');
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
