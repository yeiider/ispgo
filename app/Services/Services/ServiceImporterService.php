<?php

namespace App\Services\Services;

use App\Models\Services\Service;
use App\Models\Services\Plan;
use App\Models\Router;
use App\Models\BillingCycle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceImporterService
{
    /**
     * Allowed status values for a service.
     */
    const VALID_STATUSES = ['active', 'inactive', 'suspended', 'cancelled', 'pending', 'free'];

    /**
     * Allowed service types.
     */
    const VALID_TYPES = ['ftth', 'adsl', 'satellite'];

    /**
     * Phase 1: Dry-run CSV validation (Does not save permanent data)
     */
    public function validateCsv(string $path, string $mode = 'update_only'): array
    {
        if (!file_exists($path)) {
            return [
                'valid' => false,
                'message' => 'El archivo no existe o no se puede leer.',
                'summary' => ['total_rows' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors_count' => 1],
                'created_records' => [],
                'updated_records' => [],
                'skipped_records' => [],
                'errors' => [['row' => 0, 'name' => 'Archivo', 'error' => 'No se pudo abrir el archivo CSV.']]
            ];
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [
                'valid' => false,
                'message' => 'No se pudo abrir el archivo CSV.',
                'summary' => ['total_rows' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors_count' => 1],
                'created_records' => [],
                'updated_records' => [],
                'skipped_records' => [],
                'errors' => [['row' => 0, 'name' => 'Archivo', 'error' => 'No se pudo abrir el archivo CSV.']]
            ];
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [
                'valid' => false,
                'message' => 'El archivo CSV está vacío.',
                'summary' => ['total_rows' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors_count' => 1],
                'created_records' => [],
                'updated_records' => [],
                'skipped_records' => [],
                'errors' => [['row' => 0, 'name' => 'Archivo', 'error' => 'El archivo CSV no contiene encabezados.']]
            ];
        }

        $headers = array_map(function ($h) {
            return trim(mb_strtolower($h));
        }, $headers);

        $rowNumber = 1;
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $createdRecords = [];
        $updatedRecords = [];
        $skippedRecords = [];
        $errors = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                if (count($row) === 1 && trim($row[0]) === '') {
                    continue;
                }

                $data = [];
                foreach ($headers as $i => $key) {
                    $data[$key] = isset($row[$i]) && trim($row[$i]) !== '' ? trim($row[$i]) : null;
                }

                $parsed = $this->parseRowData($data);
                $rowName = "Fila {$rowNumber}";

                if (empty($parsed['id'])) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => $rowName,
                        'error' => 'Falta el ID del servicio obligatorio (id o service_id).'
                    ];
                    continue;
                }

                $service = Service::withoutGlobalScopes()->find($parsed['id']);
                if (!$service) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "Servicio #{$parsed['id']}",
                        'error' => "El servicio con ID '{$parsed['id']}' no existe en la base de datos."
                    ];
                    continue;
                }

                $clientName = $service->customer
                    ? trim($service->customer->first_name . ' ' . $service->customer->last_name)
                    : "Servicio #{$service->id}";

                // Validate optional fields if present
                if (isset($parsed['service_status']) && !in_array($parsed['service_status'], self::VALID_STATUSES, true)) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "{$clientName} (ID: {$service->id})",
                        'error' => "Estado '{$parsed['service_status']}' inválido. Permitidos: " . implode(', ', self::VALID_STATUSES)
                    ];
                    continue;
                }

                if (isset($parsed['service_type']) && !in_array(strtolower($parsed['service_type']), self::VALID_TYPES, true)) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "{$clientName} (ID: {$service->id})",
                        'error' => "Tipo de servicio '{$parsed['service_type']}' inválido. Permitidos: " . implode(', ', self::VALID_TYPES)
                    ];
                    continue;
                }

                if (!empty($parsed['plan_id']) && !Plan::find($parsed['plan_id'])) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "{$clientName} (ID: {$service->id})",
                        'error' => "El plan con ID '{$parsed['plan_id']}' no existe."
                    ];
                    continue;
                }

                if (!empty($parsed['router_id']) && !Router::find($parsed['router_id'])) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "{$clientName} (ID: {$service->id})",
                        'error' => "El router con ID '{$parsed['router_id']}' no existe."
                    ];
                    continue;
                }

                if (!empty($parsed['billing_cycle_id']) && !BillingCycle::find($parsed['billing_cycle_id'])) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "{$clientName} (ID: {$service->id})",
                        'error' => "El ciclo de facturación con ID '{$parsed['billing_cycle_id']}' no existe."
                    ];
                    continue;
                }

                $updated++;
                $updatedRecords[] = [
                    'row' => $rowNumber,
                    'name' => $clientName,
                    'document' => "ID Servicio: #{$service->id}"
                ];
            }
        } finally {
            fclose($handle);
            DB::rollBack();
        }

        $isValid = empty($errors);
        $totalRows = $rowNumber - 1;

        return [
            'valid' => $isValid,
            'message' => $isValid
                ? "Validación exitosa. Se actualizarán {$updated} servicios."
                : "El archivo CSV contiene " . count($errors) . " error(es) de validación.",
            'summary' => [
                'total_rows' => $totalRows,
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors_count' => count($errors),
            ],
            'created_records' => $createdRecords,
            'updated_records' => $updatedRecords,
            'skipped_records' => $skippedRecords,
            'errors' => $errors,
        ];
    }

    /**
     * Phase 2: Execute actual CSV import / update
     */
    public function importCsv(string $path, string $mode = 'update_only'): array
    {
        if (!file_exists($path) || ($handle = fopen($path, 'r')) === false) {
            return [
                'success' => false,
                'message' => 'No se pudo abrir el archivo CSV para la importación.',
                'stats' => ['created' => 0, 'updated' => 0, 'skipped' => 0, 'total' => 0],
                'errors' => [['row' => 0, 'name' => 'Archivo', 'error' => 'No se pudo abrir el archivo CSV.']]
            ];
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [
                'success' => false,
                'message' => 'El archivo CSV no contiene encabezados válidos.',
                'stats' => ['created' => 0, 'updated' => 0, 'skipped' => 0, 'total' => 0],
                'errors' => [['row' => 0, 'name' => 'Archivo', 'error' => 'El archivo CSV está vacío.']]
            ];
        }

        $headers = array_map(function ($h) {
            return trim(mb_strtolower($h));
        }, $headers);

        $rowNumber = 1;
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $createdRecords = [];
        $updatedRecords = [];
        $errors = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                if (count($row) === 1 && trim($row[0]) === '') {
                    continue;
                }

                $data = [];
                foreach ($headers as $i => $key) {
                    $data[$key] = isset($row[$i]) && trim($row[$i]) !== '' ? trim($row[$i]) : null;
                }

                $parsed = $this->parseRowData($data);
                if (empty($parsed['id'])) {
                    continue;
                }

                $service = Service::withoutGlobalScopes()->find($parsed['id']);
                if (!$service) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "Servicio #{$parsed['id']}",
                        'error' => "El servicio con ID '{$parsed['id']}' no existe."
                    ];
                    continue;
                }

                $clientName = $service->customer
                    ? trim($service->customer->first_name . ' ' . $service->customer->last_name)
                    : "Servicio #{$service->id}";

                // Update non-null fields
                $updateData = [];

                if (array_key_exists('sn', $parsed) && $parsed['sn'] !== null) {
                    $updateData['sn'] = $parsed['sn'];
                }
                if (array_key_exists('service_ip', $parsed) && $parsed['service_ip'] !== null) {
                    $updateData['service_ip'] = $parsed['service_ip'];
                }
                if (array_key_exists('mac_address', $parsed) && $parsed['mac_address'] !== null) {
                    $updateData['mac_address'] = $parsed['mac_address'];
                }
                if (array_key_exists('service_status', $parsed) && $parsed['service_status'] !== null) {
                    $updateData['service_status'] = $parsed['service_status'];
                }
                if (array_key_exists('service_type', $parsed) && $parsed['service_type'] !== null) {
                    $updateData['service_type'] = strtolower($parsed['service_type']);
                }
                if (array_key_exists('plan_id', $parsed) && $parsed['plan_id'] !== null) {
                    $updateData['plan_id'] = $parsed['plan_id'];
                    $updateData['internet_plan_id'] = $parsed['plan_id'];
                }
                if (array_key_exists('router_id', $parsed) && $parsed['router_id'] !== null) {
                    $updateData['router_id'] = $parsed['router_id'];
                }
                if (array_key_exists('billing_cycle_id', $parsed) && $parsed['billing_cycle_id'] !== null) {
                    $updateData['billing_cycle_id'] = $parsed['billing_cycle_id'];
                    $updateData['billing_cycle'] = $parsed['billing_cycle_id'];
                }
                if (array_key_exists('activation_date', $parsed) && $parsed['activation_date'] !== null) {
                    $updateData['activation_date'] = $parsed['activation_date'];
                }
                if (array_key_exists('installation_date', $parsed) && $parsed['installation_date'] !== null) {
                    $updateData['installation_date'] = $parsed['installation_date'];
                }
                if (array_key_exists('unu_latitude', $parsed) && $parsed['unu_latitude'] !== null) {
                    $updateData['unu_latitude'] = $parsed['unu_latitude'];
                }
                if (array_key_exists('unu_longitude', $parsed) && $parsed['unu_longitude'] !== null) {
                    $updateData['unu_longitude'] = $parsed['unu_longitude'];
                }
                if (array_key_exists('service_notes', $parsed) && $parsed['service_notes'] !== null) {
                    $updateData['service_notes'] = $parsed['service_notes'];
                }
                if (array_key_exists('username_router', $parsed) && $parsed['username_router'] !== null) {
                    $updateData['username_router'] = $parsed['username_router'];
                }
                if (array_key_exists('password_router', $parsed) && $parsed['password_router'] !== null) {
                    $updateData['password_router'] = $parsed['password_router'];
                }

                if (!empty($updateData)) {
                    $service->update($updateData);
                }

                $updated++;
                $updatedRecords[] = [
                    'row' => $rowNumber,
                    'name' => $clientName,
                    'document' => "ID Servicio: #{$service->id}"
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error executing service bulk import: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado al procesar la actualización de servicios.',
                'stats' => ['created' => 0, 'updated' => 0, 'skipped' => 0, 'total' => 0],
                'errors' => [['row' => 0, 'name' => 'Sistema', 'error' => $e->getMessage()]]
            ];
        } finally {
            fclose($handle);
        }

        $totalRows = $rowNumber - 1;

        return [
            'success' => empty($errors),
            'message' => "Proceso de actualización masiva finalizado. Se actualizaron {$updated} servicios.",
            'stats' => [
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'total' => $totalRows,
            ],
            'created_records' => $createdRecords,
            'updated_records' => $updatedRecords,
            'errors' => $errors,
        ];
    }

    /**
     * Map CSV header keys to standard service field names.
     */
    protected function parseRowData(array $data): array
    {
        $idKeys = ['id', 'service_id', 'id_servicio', 'servicio_id'];
        $snKeys = ['sn', 'serie', 'serial', 'sn_ont', 'sn_onu', 'serial_number'];
        $ipKeys = ['service_ip', 'ip', 'ip_servicio', 'direccion_ip'];
        $macKeys = ['mac_address', 'mac', 'direccion_mac'];
        $statusKeys = ['service_status', 'estado', 'status', 'estado_servicio'];
        $typeKeys = ['service_type', 'tipo', 'tipo_servicio', 'type'];
        $planKeys = ['plan_id', 'id_plan', 'plan'];
        $routerKeys = ['router_id', 'id_router', 'router'];
        $cycleKeys = ['billing_cycle_id', 'ciclo_id', 'id_ciclo_facturacion', 'billing_cycle'];
        $actDateKeys = ['activation_date', 'fecha_activacion', 'fecha_de_activacion'];
        $instDateKeys = ['installation_date', 'fecha_instalacion', 'fecha_de_instalacion'];
        $latKeys = ['unu_latitude', 'latitude', 'latitud'];
        $lngKeys = ['unu_longitude', 'longitude', 'longitud'];
        $notesKeys = ['service_notes', 'notas', 'observaciones'];
        $userRouterKeys = ['username_router', 'usuario_router'];
        $passRouterKeys = ['password_router', 'clave_router'];

        $parsed = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $idKeys, true) && $value !== null) {
                $parsed['id'] = (int) $value;
            } elseif (in_array($key, $snKeys, true)) {
                $parsed['sn'] = $value;
            } elseif (in_array($key, $ipKeys, true)) {
                $parsed['service_ip'] = $value;
            } elseif (in_array($key, $macKeys, true)) {
                $parsed['mac_address'] = $value;
            } elseif (in_array($key, $statusKeys, true)) {
                $parsed['service_status'] = strtolower((string) $value);
            } elseif (in_array($key, $typeKeys, true)) {
                $parsed['service_type'] = strtolower((string) $value);
            } elseif (in_array($key, $planKeys, true)) {
                $parsed['plan_id'] = $value;
            } elseif (in_array($key, $routerKeys, true)) {
                $parsed['router_id'] = $value;
            } elseif (in_array($key, $cycleKeys, true)) {
                $parsed['billing_cycle_id'] = $value;
            } elseif (in_array($key, $actDateKeys, true)) {
                $parsed['activation_date'] = $value;
            } elseif (in_array($key, $instDateKeys, true)) {
                $parsed['installation_date'] = $value;
            } elseif (in_array($key, $latKeys, true)) {
                $parsed['unu_latitude'] = $value;
            } elseif (in_array($key, $lngKeys, true)) {
                $parsed['unu_longitude'] = $value;
            } elseif (in_array($key, $notesKeys, true)) {
                $parsed['service_notes'] = $value;
            } elseif (in_array($key, $userRouterKeys, true)) {
                $parsed['username_router'] = $value;
            } elseif (in_array($key, $passRouterKeys, true)) {
                $parsed['password_router'] = $value;
            }
        }

        return $parsed;
    }
}
