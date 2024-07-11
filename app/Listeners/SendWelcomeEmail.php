<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(CustomerCreated $event): void
    {
        $customer = $event->customer;
        // Enviar el correo electrónico de bienvenida
        Mail::to($customer->email_address)->send(new WelcomeMail($customer));
    }
}
