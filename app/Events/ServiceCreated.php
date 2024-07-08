<?php

namespace App\Events;

use App\Models\Services\Service;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Service $service;

    /**
     * Create a new event instance.
     */
    public function __construct(Service $service)
    {

        $this->service = $service;
    }

}
