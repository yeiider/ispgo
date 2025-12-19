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

class UpdateServiceIpMacJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $service_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($service_id)
    {
        $this->service_id = $service_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApiManager $apiManager)
    {
        Log::info("Starting UpdateServiceIpMacJob for Service ID: {$this->service_id}");

        try {
            // Find Service
            $service = Service::find($this->service_id);

            if (!$service) {
                Log::error("Service not found for ID: {$this->service_id}");
                return;
            }

            if (empty($service->sn)) {
                Log::warning("Service {$this->service_id} has no SN assigned. Skipping.");
                return;
            }

            // Fetch ONU details from SmartOLT
            $response = $apiManager->getOnuFullStatusByExternalId($service->sn);
            $data = $response->json();

            if (!isset($data['full_status_json'])) {
                 Log::error("SmartOLT response missing 'full_status_json' for SN: {$service->sn}", ['response' => $data]);
                 return;
            }
            
            $wanInterfaces = $data['full_status_json']['ONU WAN Interfaces'] ?? null;
            
            if (!$wanInterfaces) {
                Log::error("SmartOLT response missing 'ONU WAN Interfaces' for SN: {$service->sn}");
                return;
            }

            $ipAddress = $wanInterfaces['IPv4 address'] ?? null;
            $macAddress = $wanInterfaces['MAC address'] ?? null;

            if (!$ipAddress || !$macAddress) {
                 Log::error("Could not find IP or MAC in SmartOLT response for SN: {$service->sn}", ['wan_interfaces' => $wanInterfaces]);
                 return;
            }

            $normalizedMac = $this->normalizeMac($macAddress);

            Log::info("Found IP: {$ipAddress}, MAC: {$normalizedMac} for SN: {$service->sn}");

            // Update Service with new details
            $service->mac_address = $normalizedMac;
            $service->service_ip = $ipAddress;
            $service->save();

            Log::info("Service {$this->service_id} updated successfully.");

        } catch (\Exception $e) {
            Log::error("Error in UpdateServiceIpMacJob: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Fail safely without retrying indefinitely unless configured
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
