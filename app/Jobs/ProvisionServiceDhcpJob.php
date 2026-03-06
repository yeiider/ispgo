<?php

namespace App\Jobs;

use App\Models\Services\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Services\MikrotikApiClient;

class ProvisionServiceDhcpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $service_id;
    protected $dhcpServer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($service_id, $dhcpServer)
    {
        $this->service_id = $service_id;
        $this->dhcpServer = $dhcpServer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MikrotikApiClient $mikrotikClient)
    {
        Log::info("Starting ProvisionServiceDhcpJob for Service ID: {$this->service_id}, DHCP Server: {$this->dhcpServer}");

        try {
            // Find Service with Plan
            $service = Service::with('plan', 'customer')->find($this->service_id);

            if (!$service) {
                Log::error("Service not found for ID: {$this->service_id}");
                return;
            }

            if (empty($service->service_ip) || empty($service->mac_address)) {
                Log::warning("Service {$this->service_id} missing IP or MAC. Skipping provision.");
                return;
            }

            if (!$service->plan) {
                 Log::warning("Service {$this->service_id} has no plan assigned. Skipping provision.");
                 return;
            }

            // Prepare Data
            $queueName = (string) $service->customer->full_name;
            $upload = $service->plan->upload_speed . 'M';
            $download = $service->plan->download_speed . 'M';
            $maxLimit = "{$upload}/{$download}";
            $comment = "Service: {$service->id} - {$service->customer->full_name}";

            // Initialize Client with Router ID
            if ($service->router_id) {
                 $mikrotikClient = new MikrotikApiClient($service->router_id);
            }

            Log::info("Provisioning Mikrotik for IP: {$service->service_ip}, Server: {$this->dhcpServer}");

            $result = $mikrotikClient->provisionService(
                $service->mac_address,
                $service->service_ip,
                $this->dhcpServer,
                $queueName,
                $maxLimit,
                $comment
            );

            Log::info("Mikrotik Provision result for Service {$this->service_id}", ['result' => $result]);

        } catch (\Exception $e) {
            Log::error("Error in ProvisionServiceDhcpJob: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->fail($e);
        }
    }
}
