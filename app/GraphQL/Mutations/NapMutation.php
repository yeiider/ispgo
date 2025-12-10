<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Ispgo\NapManager\Models\NapBox;
use Ispgo\NapManager\Models\NapPort;

class NapMutation
{
    public function createNapBox($root, array $args)
    {
        $input = $args['input'];
        $napBox = new NapBox();
        $napBox->fill($this->onlyNapBoxFillable($input));
        $napBox->save();
        return $napBox->fresh('ports');
    }

    public function updateNapBox($root, array $args)
    {
        $napBox = NapBox::findOrFail($args['id']);
        $napBox->fill($this->onlyNapBoxFillable($args['input']));
        $napBox->save();
        return $napBox->fresh('ports');
    }

    public function createNapPort($root, array $args)
    {
        $input = $args['input'];
        // Validate unique port number per nap box
        $exists = NapPort::where('nap_box_id', $input['nap_box_id'] ?? null)
            ->where('port_number', $input['port_number'] ?? null)
            ->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'port_number' => ['Port number already exists for this NapBox.']
            ]);
        }

        $port = new NapPort();
        $port->fill($this->onlyNapPortFillable($input));
        $port->save();
        return $port;
    }

    public function updateNapPort($root, array $args)
    {
        $port = NapPort::findOrFail($args['id']);
        $port->fill($this->onlyNapPortFillable($args['input']));
        $port->save();
        return $port;
    }

    public function assignServiceToNapPort($root, array $args)
    {
        return DB::transaction(function () use ($args) {
            $port = NapPort::lockForUpdate()->findOrFail($args['nap_port_id']);

            if ($port->status !== NapPort::STATUS_AVAILABLE && $port->service_id && (int)$port->service_id !== (int)$args['service_id']) {
                throw ValidationException::withMessages([
                    'nap_port_id' => ['Port is not available.']
                ]);
            }

            // If assigning different service to an occupied port, release first
            if ($port->service_id && (int)$port->service_id !== (int)$args['service_id']) {
                $port->service_id = null;
            }

            $port->service_id = (int)$args['service_id'];
            $port->status = NapPort::STATUS_OCCUPIED;
            $port->save();

            return $port->fresh();
        });
    }

    public function releaseNapPort($root, array $args)
    {
        $port = NapPort::findOrFail($args['nap_port_id']);
        $port->service_id = null;
        $port->status = NapPort::STATUS_AVAILABLE;
        $port->save();
        return $port;
    }

    public function assignRouterToNapBox($root, array $args)
    {
        $napBox = NapBox::findOrFail($args['nap_box_id']);
        $napBox->router_id = $args['router_id'];
        $napBox->save();
        return $napBox;
    }

    private function onlyNapBoxFillable(array $input): array
    {
        return array_intersect_key($input, array_flip([
            'name','code','address','latitude','longitude','status','capacity','technology_type','installation_date','brand','model','distribution_order','parent_nap_id','router_id','fiber_color'
        ]));
    }

    private function onlyNapPortFillable(array $input): array
    {
        return array_intersect_key($input, array_flip([
            'nap_box_id','port_number','port_name','status','connection_type','service_id','code','color','last_signal_check','signal_strength','port_config','notes','technician_notes','last_maintenance','warranty_until'
        ]));
    }
}
