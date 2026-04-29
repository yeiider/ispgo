<?php

namespace App\GraphQL\Mutations;

use App\Jobs\CompleteOnuActivationJob;
use App\Jobs\ProcessOnuAuthorization;
use App\Models\Ticket;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class CompleteInstallationMutation
{
    public function __construct(protected ApiManager $apiManager) {}

    public function handle($root, array $args): array
    {
        try {
            $ticket = Ticket::with(['service.customer.addresses', 'service.plan'])->find($args['ticket_id']);

            if (!$ticket) {
                return ['success' => false, 'message' => 'Ticket no encontrado'];
            }

            if ($ticket->issue_type !== 'installation') {
                return ['success' => false, 'message' => 'El ticket no es de tipo instalación'];
            }

            $service = $ticket->service;
            if (!$service) {
                return ['success' => false, 'message' => 'No hay servicio asociado al ticket'];
            }

            $customer = $service->customer;
            if (!$customer) {
                return ['success' => false, 'message' => 'No hay cliente asociado al servicio'];
            }

            $payload = [
                'olt_id'             => $args['olt_id'],
                'pon_type'           => $args['pon_type'],
                'board'              => $args['board'],
                'port'               => $args['port'],
                'sn'                 => $args['sn'],
                'vlan'               => $args['vlan'],
                'onu_type'           => $args['onu_type'],
                'zone'               => $args['zone'],
                'onu_mode'           => $args['onu_mode'],
                'name'               => strtoupper($this->limpiarCadena($customer->full_name)),
                'address_or_comment' => preg_replace(
                    '/[^a-zA-Z0-9]/',
                    ' ',
                    str_replace('ñ', 'n', str_replace('Ñ', 'N', $customer->addresses()->first()->address ?? 'N/A'))
                ),
            ];

            if (!empty($args['odb'])) {
                $payload['odb'] = $args['odb'];
            }

            Log::info('CompleteInstallationMutation: autorizando ONU', [
                'ticket_id' => $args['ticket_id'],
                'sn'        => $args['sn'],
            ]);

            // Paso 1: Autorizar ONU (sincrónico)
            $response = $this->apiManager->authorizeOnu($payload);
            $data = $response->json();

            if (($data['status'] ?? false) !== true) {
                return [
                    'success' => false,
                    'message' => $data['error'] ?? 'Error al autorizar la ONU',
                ];
            }

            // Guardar SN y activar servicio
            $service->sn = $args['sn'];
            $service->service_status = 'active';
            $service->save();

            // Pasos 2-4: mgmt IP DHCP → TR069 → WAN mode DHCP (30 segundos)
            CompleteOnuActivationJob::dispatch($args['sn'], $args['vlan_mgmt'])
                ->delay(now()->addSeconds(30))
                ->onQueue('redis');

            // Provisionamiento Mikrotik (4 minutos, después de la activación)
            ProcessOnuAuthorization::dispatch($service->id, $args['sn'], $args['vlan'], $args['olt_id'])
                ->delay(now()->addMinutes(4))
                ->onQueue('redis');

            // Cerrar el ticket
            $ticket->status = 'resolved';
            $ticket->resolution_notes = $args['resolution_notes'] ?? 'Instalación completada y ONU activada correctamente.';
            $ticket->save();

            Log::info('CompleteInstallationMutation: completado', [
                'ticket_id' => $args['ticket_id'],
                'sn'        => $args['sn'],
            ]);

            return [
                'success' => true,
                'message' => 'Instalación completada. La ONU se está configurando en segundo plano.',
            ];

        } catch (\Exception $e) {
            Log::error('CompleteInstallationMutation: error', [
                'message' => $e->getMessage(),
                'args'    => $args,
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function limpiarCadena(string $str): string
    {
        $originales  = ['À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ'];
        $modificadas = ['A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','d','n','o','o','o','o','o','o','u','u','u','u','y','y'];
        return str_replace($originales, $modificadas, $str);
    }
}
