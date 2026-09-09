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

            // Obtener dirección / comentario para SmartOLT de forma 100% segura
            $rawAddress = $args['address_or_comment'] ?? null;
            if (empty($rawAddress)) {
                if ($customer->relationLoaded('addresses') && $customer->addresses && $customer->addresses->isNotEmpty()) {
                    $rawAddress = $customer->addresses->first()->address;
                } elseif (method_exists($customer, 'addresses') && $customer->addresses()->exists()) {
                    $rawAddress = $customer->addresses()->first()?->address;
                }
                if (empty($rawAddress) && !empty($service->service_location)) {
                    $rawAddress = $service->service_location;
                }
            }
            $cleanAddress = $this->sanitizeSmartOltAddress($rawAddress);

            // Obtener y sanitizar nombre del cliente
            $customerName = $args['name'] ?? $customer->full_name ?? ($customer->first_name . ' ' . $customer->last_name);
            $cleanName = $this->sanitizeSmartOltName($customerName, $service->id);

            // Obtener y sanitizar zona
            $cleanZone = $this->sanitizeSmartOltZone($args['zone'] ?? '');

            $payload = [
                'olt_id'             => $args['olt_id'],
                'pon_type'           => $args['pon_type'],
                'board'              => $args['board'],
                'port'               => $args['port'],
                'sn'                 => $args['sn'],
                'vlan'               => $args['vlan'],
                'onu_type'           => $args['onu_type'],
                'zone'               => $cleanZone,
                'onu_mode'           => $this->normalizeSmartOltOnuMode($args['onu_mode'] ?? 'Routing'),
                'name'               => $cleanName,
                'address_or_comment' => $cleanAddress,
            ];

            $cleanOdb = $this->sanitizeSmartOltOdb($args['odb'] ?? null);
            if (!empty($cleanOdb)) {
                $payload['odb'] = $cleanOdb;
            }

            // Perfil de velocidad opcional
            $speedProfile = $args['speed_profile'] ?? null;
            if (empty($speedProfile) && $service->plan) {
                $speedProfile = $service->plan->name;
            }
            if (!empty($speedProfile)) {
                $cleanSpeedProfile = $this->limpiarCadena($speedProfile);
                $cleanSpeedProfile = preg_replace('~[^a-zA-Z0-9_\-\.\s]~', '', $cleanSpeedProfile);
                $cleanSpeedProfile = trim($cleanSpeedProfile);
                if (!empty($cleanSpeedProfile)) {
                    $payload['download_speed_profile_name'] = $cleanSpeedProfile;
                    $payload['upload_speed_profile_name'] = $cleanSpeedProfile;
                }
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

    /**
     * Sanitiza la dirección o comentario para SmartOLT.
     * Regla oficial SmartOLT:
     * "Address or comment can contain only alphanumeric characters, spaces and the following characters: @$&()-.+,/_:;"
     * IMPORTANTE: No se permiten '#' ni comillas ni acentos UTF-8.
     */
    private function sanitizeSmartOltAddress(?string $address): string
    {
        if (empty($address)) {
            return 'N/A';
        }

        // 1. Convertir letras con tildes y caracteres especiales a ASCII
        $clean = $this->limpiarCadena($address);

        // 2. Convertir numerales y símbolos de dirección hispanos
        $clean = str_replace(['#', '№'], 'No. ', $clean);
        $clean = str_replace(['°', 'º'], '.', $clean);

        // 3. Filtrar estrictamente según la regla de SmartOLT:
        // Letras a-z, A-Z, dígitos 0-9, espacios y los caracteres @ $ & ( ) - . + , / _ : ;
        // Usamos delimitador ~ para no entrar en conflicto con la barra diagonal /
        $clean = preg_replace('~[^a-zA-Z0-9\s@$&()\-.\+,/_:;]~', ' ', $clean);

        // 4. Normalizar espacios en blanco múltiples
        $clean = trim(preg_replace('~\s+~', ' ', $clean));

        // 5. Limitar longitud máxima de seguridad (SmartOLT suele permitir hasta 100 caracteres)
        if (strlen($clean) > 100) {
            $clean = substr($clean, 0, 100);
        }

        return empty($clean) ? 'N/A' : $clean;
    }

    /**
     * Sanitiza el nombre para SmartOLT:
     * "Name can contain only alphanumeric characters, spaces and the following characters: @$&()-.+,/_"
     */
    private function sanitizeSmartOltName(?string $name, $fallbackId = ''): string
    {
        if (empty($name)) {
            return 'CLIENTE ' . $fallbackId;
        }

        $clean = strtoupper($this->limpiarCadena($name));
        $clean = str_replace(['#', '№', '°', 'º', "'", '"', '`'], ' ', $clean);
        $clean = preg_replace('~[^a-zA-Z0-9\s@$&()\-.\+,/_]~', ' ', $clean);
        $clean = trim(preg_replace('~\s+~', ' ', $clean));

        if (strlen($clean) > 60) {
            $clean = substr($clean, 0, 60);
        }

        return empty($clean) ? ('CLIENTE ' . $fallbackId) : $clean;
    }

    /**
     * Sanitiza la zona para SmartOLT:
     * "The Zone can contain only alphanumeric characters, spaces, underscore and the dash (-) character"
     */
    private function sanitizeSmartOltZone(string $zone): string
    {
        $clean = $this->limpiarCadena($zone);
        $clean = preg_replace('~[^a-zA-Z0-9\s_\-]~', ' ', $clean);
        $clean = trim(preg_replace('~\s+~', ' ', $clean));
        return empty($clean) ? $zone : $clean;
    }

    /**
     * Sanitiza la caja ODB para SmartOLT:
     * "The ODB can contain only alphanumeric characters, spaces, underscore and the dash (-) character"
     */
    private function sanitizeSmartOltOdb(?string $odb): ?string
    {
        if (empty($odb)) {
            return null;
        }

        $clean = $this->limpiarCadena($odb);
        $clean = str_replace(['#', '№'], 'No ', $clean);
        $clean = preg_replace('~[^a-zA-Z0-9\s_\-]~', ' ', $clean);
        $clean = trim(preg_replace('~\s+~', ' ', $clean));

        return empty($clean) ? null : substr($clean, 0, 50);
    }

    /**
     * Normaliza el modo de la ONU para SmartOLT ('Routing' o 'Bridging')
     */
    private function normalizeSmartOltOnuMode(?string $mode): string
    {
        $m = strtolower(trim($mode ?? ''));
        if (str_contains($m, 'bridge') || str_contains($m, 'bridging')) {
            return 'Bridging';
        }
        return 'Routing';
    }

    private function limpiarCadena(string $str): string
    {
        $originales  = ['À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ'];
        $modificadas = ['A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','d','n','o','o','o','o','o','o','u','u','u','u','y','y'];
        return str_replace($originales, $modificadas, $str);
    }
}
