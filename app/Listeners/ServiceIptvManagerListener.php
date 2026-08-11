<?php

namespace App\Listeners;

use App\Events\ServiceUpdateStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Services\Iptv\XuiClient;
use App\Settings\Iptv\ProviderIptv;

class ServiceIptvManagerListener
{
    use InteractsWithQueue;

    public $queue = 'redis';
    public $tries = 3;
    public $timeout = 120;
    public $delay = 10;

    /**
     * Handle the event.
     *
     * @param ServiceUpdateStatus $event
     * @return void
     */
    public function handle(ServiceUpdateStatus $event)
    {
        if (!ProviderIptv::getEnabled()) {
            Log::info("IPTV XUI.one integration is not enabled.");
            return;
        }

        $service = $event->service;

        // Retrieve related IPTV line user
        $iptvLineUser = $service->iptvLineUser;

        if (!$iptvLineUser || empty($iptvLineUser->line_id)) {
            Log::info("Service ID {$service->id} does not have a linked IPTV Line User or line_id is empty.");
            return;
        }

        $lineId = (int) $iptvLineUser->line_id;
        $status = $service->service_status;

        $xuiClient = new XuiClient();

        try {
            if ($status === 'active') {
                Log::info("Activating IPTV Line User (ID: {$lineId}) for service {$service->id}");
                $response = $xuiClient->enableLine($lineId);
                $data = $response->json();

                if ($response->successful() && ($data['status'] ?? false) === true) {
                    $iptvLineUser->status = 'active';
                    $iptvLineUser->save();
                    Log::info("IPTV Line User (ID: {$lineId}) enabled successfully.");
                } else {
                    Log::error("Failed to enable IPTV Line User (ID: {$lineId}) in XUI.one: " . ($data['message'] ?? 'Unknown API error'));
                }
            } elseif ($status === 'suspended') {
                Log::info("Suspending IPTV Line User (ID: {$lineId}) for service {$service->id}");
                $response = $xuiClient->disableLine($lineId);
                $data = $response->json();

                if ($response->successful() && ($data['status'] ?? false) === true) {
                    $iptvLineUser->status = 'disabled';
                    $iptvLineUser->save();
                    Log::info("IPTV Line User (ID: {$lineId}) disabled successfully.");
                } else {
                    Log::error("Failed to disable IPTV Line User (ID: {$lineId}) in XUI.one: " . ($data['message'] ?? 'Unknown API error'));
                }
            }
        } catch (\Exception $e) {
            Log::error("Exception in ServiceIptvManagerListener for line {$lineId}: " . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
