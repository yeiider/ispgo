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

            // Aceptar variantes de instalación: 'installation', 'instalacion', 'instalación', 'Instalación', etc.
            $issueTypeNorm = mb_strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $ticket->issue_type ?? ''));
            $isInstallation = in_array($issueTypeNorm, ['installation', 'instalacion', 'activacion', 'cambio de equipo', 'falla tecnica']);

            $service = $ticket->service;
            if (!$service) {
                return ['success' => false, 'message' => 'No hay servicio asociado al ticket'];
            }

            // Si el servicio no tiene SN o está pendiente de activación, permitir autorizar la ONU
            if (!$isInstallation && empty($service->sn)) {
                $isInstallation = true;
            }

            if (!$isInstallation) {
                return ['success' => false, 'message' => "El ticket es de tipo '{$ticket->issue_type}'. Se requiere un ticket de instalación o servicio sin ONU asignada."];
            }

            $customer = $service->customer;
            if (!$customer) {
                return ['success' => false, 'message' => 'No hay cliente asociado al servicio'];
            }

            // Obtener dirección de forma 100% segura contra nulos
            $rawAddress = null;
            if ($customer->relationLoaded('addresses') && $customer->addresses && $customer->addresses->isNotEmpty()) {
                $rawAddress = $customer->addresses->first()->address;
            } elseif (method_exists($customer, 'addresses') && $customer->addresses()->exists()) {
                $rawAddress = $customer->addresses()->first()?->address;
            }
            $cleanAddress = preg_replace('/[^a-zA-Z0-9\s@#$&()\-.\',\/_]/', ' ', str_replace(['ñ', 'Ñ'], ['n', 'N'], $rawAddress ?? 'N/A'));
            $cleanAddress = trim(preg_replace('/\s+/', ' ', $cleanAddress ?? ''));
            if (empty($cleanAddress)) {
                $cleanAddress = 'N/A';
            }

            // Obtener nombre del cliente de forma segura
            $customerName = $args['name'] ?? $customer->full_name ?? ($customer->first_name . ' ' . $customer->last_name);
            $cleanName = preg_replace('/[^a-zA-Z0-9\s@#$&()\-.\',\/_]/', '', $this->limpiarCadena($customerName));
            $cleanName = strtoupper(trim(preg_replace('/\s+/', ' ', $cleanName ?? '')));
            if (empty($cleanName)) {
                $cleanName = 'CLIENTE ' . $service->id;
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
                'name'               => $cleanName,
                'address_or_comment' => $cleanAddress,
            ];

            if (!empty($args['odb'])) {
                $payload['odb'] = $args['odb'];
            }

            // Perfil de velocidad opcional
            $speedProfile = $args['speed_profile'] ?? null;
            if (empty($speedProfile) && $service->plan) {
                $speedProfile = $service->plan->name;
            }
            if (!empty($speedProfile)) {
                $payload['download_speed_profile_name'] = $speedProfile;
                $payload['upload_speed_profile_name'] = $speedProfile;
            }

            Log::info('CompleteInstallationMutation: autorizando ONU', [
                'ticket_id' => $args['ticket_id'],
                'sn'        => $args['sn'],
                'payload'   => $payload,
            ]);

            // Paso 1: Autorizar ONU (sincrónico)
            $response = $this->apiManager->authorizeOnu($payload);
            $data = $response->json();

            Log::info('CompleteInstallationMutation: respuesta SmartOLT', [
                'status' => $response->status(),
                'data'   => $data,
            ]);

            if (($data['status'] ?? false) !== true) {
                $errorMsg = $data['error'] ?? $data['message'] ?? (is_string($data['response'] ?? null) ? $data['response'] : 'Error al autorizar la ONU en SmartOLT');
                return [
                    'success' => false,
                    'message' => $errorMsg,
                ];
            }

            // Guardar SN y activar servicio
            $service->sn = $args['sn'];
            $service->service_status = 'active';
            $service->save();

            // Pasos 2-4: Jobs de activación y provisionamiento en segundo plano (protegidos contra fallos de cola)
            try {
                $vlanMgmt = !empty($args['vlan_mgmt']) ? (int) $args['vlan_mgmt'] : 0;
                if ($vlanMgmt > 0) {
                    CompleteOnuActivationJob::dispatch($args['sn'], $vlanMgmt)
                        ->delay(now()->addSeconds(30));
                }

                // Provisionamiento Mikrotik (4 minutos, después de la activación)
                ProcessOnuAuthorization::dispatch($service->id, $args['sn'], (int) $args['vlan'], (int) $args['olt_id'])
                    ->delay(now()->addMinutes(4));
            } catch (\Throwable $queueError) {
                Log::warning('CompleteInstallationMutation: error al encolar jobs de fondo', [
                    'error' => $queueError->getMessage()
                ]);
            }

            // Cerrar el ticket
            $ticket->status = 'resolved';
            $ticket->resolution_notes = $args['resolution_notes'] ?? 'Instalación completada y ONU activada correctamente.';
            $ticket->save();

            Log::info('CompleteInstallationMutation: completado con éxito', [
                'ticket_id' => $args['ticket_id'],
                'sn'        => $args['sn'],
            ]);

            return [
                'success' => true,
                'message' => 'Instalación completada. La ONU se ha autorizado exitosamente.',
            ];

        } catch (\Throwable $e) {
            Log::error('CompleteInstallationMutation: error fatal', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'args'    => $args,
            ]);

            return ['success' => false, 'message' => 'Error al procesar instalación: ' . $e->getMessage()];
        }
    }

    private function limpiarCadena(string $str): string
    {
        $originales  = ['À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ'];
        $modificadas = ['A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','d','n','o','o','o','o','o','o','u','u','u','u','y','y'];
        return str_replace($originales, $modificadas, $str);
    }
}
