<?php

namespace App\Jobs;

use App\Models\Services\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Mikrotik\Services\MikrotikApiClient;

class ProcessOnuAuthorization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $service_id;
    protected $sn;
    protected $vlan;
    protected $olt_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($service_id, $sn, $vlan, $olt_id = null)
    {
        $this->service_id = $service_id;
        $this->sn = $sn;
        $this->vlan = $vlan;
        $this->olt_id = $olt_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApiManager $apiManager, MikrotikApiClient $mikrotikClient)
    {
        Log::info("Starting ProcessOnuAuthorization for Service ID: {$this->service_id}, SN: {$this->sn}");

        try {
            // Find Service
            $service = Service::with('plan')->find($this->service_id);

            if (!$service) {
                Log::error("Service not found for ID: {$this->service_id}");
                return;
            }

            // Fetch ONU details from SmartOLT
            $response = $apiManager->getOnuFullStatusByExternalId($this->sn);
            $data = $response->json();

            if (!isset($data['full_status_json'])) {
                 Log::error("SmartOLT response missing 'full_status_json' for SN: {$this->sn}", ['response' => $data]);
                 // Consider if we should retry or fail
                 return;
            }

            $wanInterfaces = $data['full_status_json']['ONU WAN Interfaces'] ?? null;

            if (!$wanInterfaces) {
                Log::error("SmartOLT response missing 'ONU WAN Interfaces' for SN: {$this->sn}");
                // Could be that it hasn't connected yet, maybe retry logic later?
                return;
            }

            $ipAddress = $wanInterfaces['IPv4 address'] ?? null;
            $macAddress = $wanInterfaces['MAC address'] ?? null;

            if (!$ipAddress || !$macAddress) {
                 Log::error("Could not find IP or MAC in SmartOLT response for SN: {$this->sn}", ['wan_interfaces' => $wanInterfaces]);
                 return;
            }

            // Normalize MAC address format if needed (Mikrotik usually likes bytes or colons, SmartOLT might give dots)
            // Example input: b464.1502.4ade -> needs to be checked against what mikrotik expects?
            // Usually Mikrotik handles various formats, but standardizing to XX:XX:XX:XX:XX:XX is safer.
            // Let's simple-replace dot with colon if it looks like the dot format, or just pass as is if Mikrotik library handles it.
            // Looking at the example: b464.1502.4ade. Mikrotik usually wants Colon-separated.
            // Let's do a quick normalization function here or assume MikrotikClient handles it?
            // I'll stick to a basic normalization to colon format if it's dot separated 4-4-4

            $normalizedMac = $this->normalizeMac($macAddress);

            Log::info("Found IP: {$ipAddress}, MAC: {$normalizedMac} for SN: {$this->sn}");

            // Update Service with new details
            $service->mac_address = $normalizedMac;
            $service->service_ip = $ipAddress;
            $service->save();

            // Prepare Queue Data
            if (!$service->plan) {
                 Log::warning("Service {$this->service_id} has no plan assigned. Skipping Mikrotik queue creation.");
                 return;
            }

            // Construct Max Limit (e.g., "10M/20M" for upload/download)
            // Assuming plan speeds are in Mbps. Mikrotik expects "target-upload/target-download" usually in bits or suffixed with k/M.
            // Plan model has 'download_speed' and 'upload_speed'. Assuming they are integers like 20, 50, etc representing Mbps.
            $upload = $service->plan->upload_speed . 'M';
            $download = $service->plan->download_speed . 'M';
            $maxLimit = "{$upload}/{$download}";

            // Queue Name is Service ID
            $queueName = (string) $service->customer->full_name;

            // Comment
            $comment = "Service: {$service->id} - {$service->customer->full_name}";

            // Provision Service in Mikrotik
            // Use the Mikrotik Client manually instantiated with router_id if needed.
            // The handle method injection gives a default client. The service has a router_id.

            if ($service->router_id) {
                 $mikrotikClient = new MikrotikApiClient($service->router_id);
            }

            Log::info("Provisioning Mikrotik for IP: {$ipAddress}, Queue: {$queueName}, Limit: {$maxLimit}");

            $serverName = "vlan{$this->vlan}";

            $result = $mikrotikClient->provisionService(
                $normalizedMac,
                $ipAddress,
                $serverName,
                $queueName,
                $maxLimit,
                $comment
            );

            Log::info("Mikrotik Provision result", ['result' => $result]);

        } catch (\Exception $e) {
            Log::error("Error in ProcessOnuAuthorization job: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Maybe release back to queue?
            $this->fail($e);
        }
    }

    private function normalizeMac($mac)
    {
        // Remove non-alphanumeric
        $clean = preg_replace('/[^a-fA-F0-9]/', '', $mac);
        // Split into pairs
        $parts = str_split($clean, 2);
        // Join with colons
        return implode(':', $parts);
    }
}
