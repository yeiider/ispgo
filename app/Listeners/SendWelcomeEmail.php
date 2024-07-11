<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use App\Mail\DynamicEmail;
use App\Models\EmailTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(CustomerCreated $event): void
    {
        $customer = $event->customer;

        // Obtener la plantilla de email
        $template = EmailTemplate::find(3); // ID de la plantilla de bienvenida

        if ($template) {
            // Preparar los datos para la plantilla
            $data = ['customer' => $customer];

            // Enviar el correo electrónico de bienvenida
            Mail::to($customer->email_address)->send(new DynamicEmail($data, $template));
        }
    }
}
