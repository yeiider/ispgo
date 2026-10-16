<?php

namespace App\GraphQL\Mutations;

use App\Models\NetworkTunnel;
use App\Models\Olt;
use App\Services\Oltgo\OltgoClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Configuración de túneles y OLT para OLTGO. Tras cada cambio se le pide a
 * OLTGO que recargue; si no responde, toma los cambios en su próxima lectura.
 */
class OltgoMutation
{
    public function __construct(private OltgoClient $client)
    {
    }

    public function createTunnel($root, array $args): NetworkTunnel
    {
        $input = $args['input'];
        Validator::make($input, [
            'name' => 'required',
            'endpoint_host' => 'required',
            'remote_public_key' => 'required',
            'local_address' => 'required',
        ])->validate();

        $keys = blank($input['local_private_key'] ?? null)
            ? NetworkTunnel::generateKeypair()
            : NetworkTunnel::keypairFromPrivate($input['local_private_key']);
        $tunnel = NetworkTunnel::create([
            'local_private_key' => $keys['private'],
            'local_public_key' => $keys['public'],
        ] + $input)->refresh();

        $this->reload();

        return $tunnel;
    }

    public function updateTunnel($root, array $args): NetworkTunnel
    {
        $tunnel = NetworkTunnel::findOrFail($args['id']);
        $input = $args['input'];
        if (!blank($input['local_private_key'] ?? null)) {
            $keys = NetworkTunnel::keypairFromPrivate($input['local_private_key']);
            $input['local_private_key'] = $keys['private'];
            $input['local_public_key'] = $keys['public'];
        } else {
            unset($input['local_private_key']);
        }
        $tunnel->update($input);
        $this->reload();

        return $tunnel;
    }

    public function deleteTunnel($root, array $args): array
    {
        NetworkTunnel::findOrFail($args['id'])->delete();
        $this->reload();

        return ['success' => true, 'message' => 'Túnel eliminado'];
    }

    public function createOlt($root, array $args): Olt
    {
        $input = $args['input'];
        Validator::make($input, [
            'name' => 'required',
            'model' => 'required',
            'host' => 'required',
        ])->validate();

        $olt = Olt::create($input)->refresh();
        $this->reload();

        return $olt;
    }

    public function updateOlt($root, array $args): Olt
    {
        $olt = Olt::findOrFail($args['id']);
        // Un secreto vacío significa "no cambiarlo".
        $input = Arr::where($args['input'], function ($value, $key) {
            return !(str_starts_with($key, 'snmp_') && Str::endsWith($key, ['community', 'pass']) && blank($value));
        });
        $olt->update($input);
        $this->reload();

        return $olt;
    }

    public function deleteOlt($root, array $args): array
    {
        Olt::findOrFail($args['id'])->delete();
        $this->reload();

        return ['success' => true, 'message' => 'OLT eliminada'];
    }

    private function reload(): void
    {
        try {
            $this->client->reload();
        } catch (Throwable) {
            // OLTGO relee la configuración periódicamente.
        }
    }
}
