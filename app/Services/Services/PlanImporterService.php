<?php

namespace App\Services\Services;

use App\Models\Services\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlanImporterService
{
    /**
     * Estados válidos para un plan.
     */
    const VALID_STATUSES = ['active', 'inactive'];

    /**
     * Modalidades válidas.
     */
    const VALID_MODALITIES = ['prepaid', 'postpaid'];

    /**
     * Tipos de plan válidos.
     */
    const VALID_PLAN_TYPES = ['internet', 'television', 'telephonic'];

    /**
     * Fase 1: Validar CSV en modo dry-run (sin guardar datos permanentes).
     */
    public function validateCsv(string $path, string $mode = 'create_or_update'): array
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
            $clean = preg_replace('/^\xEF\xBB\xBF/', '', trim($h));
            return trim(mb_strtolower($clean));
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
                $planName = $parsed['name'] ?? "Fila {$rowNumber}";

                $plan = null;
                if (!empty($parsed['id'])) {
                    $plan = Plan::find($parsed['id']);
                }
                if (!$plan && !empty($parsed['name'])) {
                    $plan = Plan::where('name', $parsed['name'])->first();
                }

                if (!$plan) {
                    if ($mode === 'update_only') {
                        $skipped++;
                        $skippedRecords[] = ['row' => $rowNumber, 'name' => $planName, 'reason' => 'No existe en sistema (modo actualizar solamente)'];
                        continue;
                    }

                    $validator = Validator::make($parsed, [
                        'name' => 'required|string|max:255',
                        'download_speed' => 'nullable|numeric|min:0',
                        'upload_speed' => 'nullable|numeric|min:0',
                        'monthly_price' => 'required|numeric|min:0',
                        'status' => 'nullable|in:active,inactive',
                        'modality_type' => 'nullable|in:prepaid,postpaid',
                        'plan_type' => 'nullable|in:internet,television,telephonic',
                    ]);

                    if ($validator->fails()) {
                        $msg = implode(', ', $validator->errors()->all());
                        $errors[] = ['row' => $rowNumber, 'name' => $planName, 'error' => $msg];
                        continue;
                    }

                    $created++;
                    $createdRecords[] = ['row' => $rowNumber, 'name' => $planName];
                } else {
                    if ($mode === 'create_only') {
                        $skipped++;
                        $skippedRecords[] = ['row' => $rowNumber, 'name' => $planName, 'reason' => 'Ya existe en sistema (modo crear solamente)'];
                        continue;
                    }

                    $validator = Validator::make($parsed, [
                        'name' => 'nullable|string|max:255',
                        'download_speed' => 'nullable|numeric|min:0',
                        'upload_speed' => 'nullable|numeric|min:0',
                        'monthly_price' => 'nullable|numeric|min:0',
                        'status' => 'nullable|in:active,inactive',
                        'modality_type' => 'nullable|in:prepaid,postpaid',
                        'plan_type' => 'nullable|in:internet,television,telephonic',
                    ]);

                    if ($validator->fails()) {
                        $msg = implode(', ', $validator->errors()->all());
                        $errors[] = ['row' => $rowNumber, 'name' => $planName, 'error' => $msg];
                        continue;
                    }

                    $updated++;
                    $updatedRecords[] = ['row' => $rowNumber, 'name' => $planName];
                }
            }
        } finally {
            fclose($handle);
            DB::rollBack(); // Siempre se revierte en la fase de validación
        }

        $isValid = empty($errors);

        return [
            'valid' => $isValid,
            'message' => $isValid
                ? "Verificación exitosa. Se crearán {$created}, actualizarán {$updated} y omitirán {$skipped} planes."
                : "Se encontraron " . count($errors) . " errores de validación en el CSV de planes.",
            'summary' => [
                'total_rows' => $rowNumber - 1,
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
     * Fase 2: Ejecución de importación de planes (procesamiento atómico por fila).
     */
    public function importCsv(string $path, string $mode = 'create_or_update'): array
    {

        if (!file_exists($path)) {
            return [
                'success' => false,
                'message' => 'El archivo no existe.',
                'stats' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
                'errors' => ['No se pudo encontrar el archivo CSV.']
            ];
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [
                'success' => false,
                'message' => 'No se pudo abrir el archivo.',
                'stats' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
                'errors' => ['No se pudo abrir el archivo CSV.']
            ];
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [
                'success' => false,
                'message' => 'El archivo está vacío.',
                'stats' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
                'errors' => ['El archivo CSV no contiene datos.']
            ];
        }

        $headers = array_map(function ($h) {
            $clean = preg_replace('/^\xEF\xBB\xBF/', '', trim($h));
            return trim(mb_strtolower($clean));
        }, $headers);

        $rowNumber = 1;
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $createdRecords = [];
        $updatedRecords = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if (count($row) === 1 && trim($row[0]) === '') {
                continue;
            }

            $data = [];
            foreach ($headers as $i => $key) {
                $data[$key] = isset($row[$i]) && trim($row[$i]) !== '' ? trim($row[$i]) : null;
            }

            try {
                DB::beginTransaction();

                $parsed = $this->parseRowData($data);
                $planName = $parsed['name'] ?? "Fila {$rowNumber}";

                $plan = null;
                if (!empty($parsed['id'])) {
                    $plan = Plan::find($parsed['id']);
                }
                if (!$plan && !empty($parsed['name'])) {
                    $plan = Plan::where('name', $parsed['name'])->first();
                }

                if (!$plan) {
                    if ($mode === 'update_only') {
                        $skipped++;
                        DB::rollBack();
                        continue;
                    }

                    $validator = Validator::make($parsed, [
                        'name' => 'required|string|max:255',
                        'download_speed' => 'nullable|numeric|min:0',
                        'upload_speed' => 'nullable|numeric|min:0',
                        'monthly_price' => 'required|numeric|min:0',
                        'status' => 'nullable|in:active,inactive',
                        'modality_type' => 'nullable|in:prepaid,postpaid',
                        'plan_type' => 'nullable|in:internet,television,telephonic',
                    ]);

                    if ($validator->fails()) {
                        throw new \RuntimeException(implode(', ', $validator->errors()->all()));
                    }

                    $plan = Plan::create($parsed);
                    $created++;
                    $createdRecords[] = ['row' => $rowNumber, 'name' => $planName];
                } else {
                    if ($mode === 'create_only') {
                        $skipped++;
                        DB::rollBack();
                        continue;
                    }

                    $plan->fill(array_filter($parsed, function ($val) {
                        return $val !== null;
                    }));
                    
                    if ($plan->isDirty()) {
                        $plan->save();
                        $updated++;
                        $updatedRecords[] = ['row' => $rowNumber, 'name' => $planName];
                    } else {
                        $skipped++;
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $planName ?? "Fila {$rowNumber}",
                    'error' => $e->getMessage()
                ];
                Log::error('Error importando plan en fila', ['row' => $rowNumber, 'error' => $e->getMessage()]);
            }
        }

        fclose($handle);

        $summary = "Importación de planes completada. Creados: {$created}, Actualizados: {$updated}, Omitidos: {$skipped}.";

        return [
            'success' => empty($errors),
            'message' => $summary,
            'stats' => [
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'total' => $rowNumber - 1,
            ],
            'created_records' => $createdRecords,
            'updated_records' => $updatedRecords,
            'errors' => $errors,
        ];
    }

    private function parseRowData(array $data): array
    {
        $parsed = [];

        $fieldMap = [
            'id' => 'id',
            'plan_id' => 'id',
            'name' => 'name',
            'nombre' => 'name',
            'description' => 'description',
            'descripcion' => 'description',
            'download_speed' => 'download_speed',
            'velocidad_descarga' => 'download_speed',
            'upload_speed' => 'upload_speed',
            'velocidad_carga' => 'upload_speed',
            'monthly_price' => 'monthly_price',
            'precio_mensual' => 'monthly_price',
            'precio' => 'monthly_price',
            'status' => 'status',
            'estado' => 'status',
            'modality_type' => 'modality_type',
            'modalidad' => 'modality_type',
            'plan_type' => 'plan_type',
            'tipo_plan' => 'plan_type',
            'profile_smart_olt' => 'profile_smart_olt',
            'perfil_smart_olt' => 'profile_smart_olt',
            'is_dedicated' => 'is_dedicated',
            'es_dedicado' => 'is_dedicated',
            'is_promotional' => 'is_dedicated',
            'es_promocional' => 'is_dedicated',
            'is_synchronized' => 'is_synchronized',
            'es_sincronizado' => 'is_synchronized',
            'data_limit' => 'data_limit',
            'limite_datos' => 'data_limit',
            'unlimited_data' => 'unlimited_data',
            'datos_ilimitados' => 'unlimited_data',
        ];

        foreach ($data as $key => $val) {
            $keyClean = strtolower(trim($key));
            if (isset($fieldMap[$keyClean])) {
                $targetField = $fieldMap[$keyClean];
                $parsed[$targetField] = $val !== null && trim((string)$val) !== '' ? trim((string)$val) : null;
            }
        }

        // Mapeo y valores por defecto
        if (isset($parsed['status'])) {
            $st = strtolower($parsed['status']);
            $parsed['status'] = in_array($st, ['active', 'activo', 'activa', '1', 'true']) ? 'active' : 'inactive';
        } else {
            $parsed['status'] = 'active';
        }

        if (isset($parsed['modality_type'])) {
            $mod = strtolower(trim($parsed['modality_type']));
            $parsed['modality_type'] = in_array($mod, ['prepaid', 'prepago']) ? 'prepaid' : 'postpaid';
        } else {
            $parsed['modality_type'] = 'postpaid';
        }

        if (isset($parsed['plan_type'])) {
            $pt = strtolower($parsed['plan_type']);
            if (in_array($pt, ['television', 'tv'])) {
                $parsed['plan_type'] = 'television';
            } elseif (in_array($pt, ['telephonic', 'telefono', 'telefonico'])) {
                $parsed['plan_type'] = 'telephonic';
            } else {
                $parsed['plan_type'] = 'internet';
            }
        } else {
            $parsed['plan_type'] = 'internet';
        }

        foreach (['is_dedicated', 'is_synchronized', 'unlimited_data'] as $boolField) {
            if (isset($parsed[$boolField])) {
                $b = strtolower($parsed[$boolField]);
                $parsed[$boolField] = in_array($b, ['1', 'true', 'si', 'sí', 'yes', 'on']) ? 1 : 0;
            }
        }

        if (isset($parsed['download_speed'])) {
            $parsed['download_speed'] = (float) $parsed['download_speed'];
        }
        if (isset($parsed['upload_speed'])) {
            $parsed['upload_speed'] = (float) $parsed['upload_speed'];
        }
        if (isset($parsed['monthly_price'])) {
            $parsed['monthly_price'] = (float) $parsed['monthly_price'];
        }

        return array_filter($parsed, function ($v) {
            return $v !== null;
        });
    }
}
