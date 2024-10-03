<?php

namespace App\Events;

use App\Models\Services\Service;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceSuspend
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
