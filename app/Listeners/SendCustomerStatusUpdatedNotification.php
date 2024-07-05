<?php

namespace App\Listeners;

use App\Events\CustomerStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCustomerStatusUpdatedNotification
{

    /**
     * Handle the event.
     *
     * @param CustomerStatusUpdated $event
     * @return void
     */
    public function handle(CustomerStatusUpdated $event): void
    {
        // Aquí puedes enviar una notificación, un correo, etc.
        // Ejemplo: Notificación de Laravel
        $customer = $event->customer;
        /*if ($customer->customer_status === "inactive") {
            $customer->services()->update(['service_status' => 'inactive']);
        }*/
        // Notification::send($event->customer, new CustomerStatusUpdatedNotification($event->customer));
    }
}
