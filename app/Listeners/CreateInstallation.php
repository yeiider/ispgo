<?php

namespace App\Listeners;

use App\Events\ServiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateInstallation
{

    /**
     * Handle the event.
     */
    public function handle(ServiceCreated $event): void
    {
        $service = $event->service;
        $service->createInstallation();
    }
}
