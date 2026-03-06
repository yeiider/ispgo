<?php

namespace App\GraphQL\Mutations;

use App\Jobs\UpdateServiceIpMacJob;
use App\Jobs\ProvisionServiceDhcpJob;

class ServiceActionsMutation
{
    /**
     * Update IP and MAC for a list of services by fetching from SmartOLT.
     *
     * @param  null  $root
     * @param  array  $args
     * @return array
     */
    public function updateIpMac($root, array $args): array
    {
        $serviceIds = $args['service_ids'];
        $dispatchedCount = 0;

        foreach ($serviceIds as $id) {
            UpdateServiceIpMacJob::dispatch($id)->onQueue('redis');
            $dispatchedCount++;
        }

        return [
            'success' => true,
            'message' => "Se han encolado {$dispatchedCount} servicios para actualizar IP y MAC."
        ];
    }

    /**
     * Provision DHCP binding and bandwidth queue for a list of services.
     *
     * @param  null  $root
     * @param  array  $args
     * @return array
     */
    public function provisionDhcp($root, array $args): array
    {
        $serviceIds = $args['service_ids'];
        $dhcpServer = $args['dhcp_server'];
        $dispatchedCount = 0;

        foreach ($serviceIds as $id) {
            ProvisionServiceDhcpJob::dispatch($id, $dhcpServer)->onQueue('redis');
            $dispatchedCount++;
        }

        return [
            'success' => true,
            'message' => "Se han encolado {$dispatchedCount} servicios para provisión en Mikrotik."
        ];
    }
}
