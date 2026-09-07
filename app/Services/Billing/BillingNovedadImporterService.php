<?php

namespace App\Services\Billing;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use App\Services\Billing\Calculators\NovedadCalculatorRegistry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BillingNovedadImporterService
{
    /**
     * Phase 1: Dry-run CSV validation (Does not save permanent data)
     */
    public function validateCsv(string $path, string $mode = 'create_only'): array
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
        $skipped = 0;
        $createdRecords = [];
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

                $rowParsed = $this->parseRowData($data);
                $rowName = "Fila {$rowNumber}";

                if (empty($rowParsed['service_id'])) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => $rowName,
                        'error' => 'Falta el ID del servicio obligatorio (service_id).'
                    ];
                    continue;
                }

                $service = Service::withoutGlobalScopes()->find($rowParsed['service_id']);
                if (!$service) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => "Servicio #{$rowParsed['service_id']}",
                        'error' => "El servicio con ID '{$rowParsed['service_id']}' no existe en el sistema."
                    ];
                    continue;
                }

                $clientName = $service->customer ? trim($service->customer->first_name . ' ' . $service->customer->last_name) : "Servicio #{$service->id}";

                if (empty($rowParsed['type'])) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => $clientName,
                        'error' => 'Falta el tipo de novedad obligatorio (type).'
                    ];
                    continue;
                }

                if (!in_array($rowParsed['type'], BillingNovedad::TYPES, true)) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => $clientName,
                        'error' => "El tipo de novedad '{$rowParsed['type']}' no es válido. Tipos permitidos: " . implode(', ', BillingNovedad::TYPES)
                    ];
                    continue;
                }

                $ruleError = $this->validateRowRuleAndAmount($rowParsed, $service);
                if ($ruleError) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => $clientName,
                        'error' => $ruleError
                    ];
                    continue;
                }

                $created++;
                $createdRecords[] = [
                    'row' => $rowNumber,
                    'name' => "{$clientName} ({$rowParsed['type']})",
                    'document' => "Servicio #{$service->id}"
                ];
            }
        } finally {
            fclose($handle);
            DB::rollBack();
        }

        $isValid = empty($errors);

        return [
            'valid' => $isValid,
            'message' => $isValid
                ? "Verificación exitosa. Se registrarán {$created} novedades de facturación."
                : "Se encontraron " . count($errors) . " errores de validación en el archivo CSV.",
            'summary' => [
                'total_rows' => $rowNumber - 1,
                'created' => $created,
                'updated' => 0,
                'skipped' => $skipped,
                'errors_count' => count($errors),
            ],
            'created_records' => $createdRecords,
            'updated_records' => [],
            'skipped_records' => $skippedRecords,
            'errors' => $errors,
        ];
    }

    /**
     * Phase 2: Execute actual CSV Import
     */
    public function importCsv(string $path, string $mode = 'create_only'): array
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
            return trim(mb_strtolower($h));
        }, $headers);

        $rowNumber = 1;
        $created = 0;
        $skipped = 0;
        $errors = [];
        $createdRecords = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if (count($row) === 1 && trim($row[0]) === '') {
                continue;
            }

            $data = [];
            foreach ($headers as $i => $key) {
                $data[$key] = isset($row[$i]) && trim($row[$i]) !== '' ? trim($row[$i]) : null;
            }

            $clientName = "Fila {$rowNumber}";

            try {
                DB::beginTransaction();

                $rowParsed = $this->parseRowData($data);

                if (empty($rowParsed['service_id'])) {
                    throw new \RuntimeException('Falta service_id obligatorio.');
                }

                $service = Service::withoutGlobalScopes()->find($rowParsed['service_id']);
                if (!$service) {
                    throw new \RuntimeException("El servicio con ID '{$rowParsed['service_id']}' no existe.");
                }

                $clientName = $service->customer ? trim($service->customer->first_name . ' ' . $service->customer->last_name) : "Servicio #{$service->id}";

                if (empty($rowParsed['type']) || !in_array($rowParsed['type'], BillingNovedad::TYPES, true)) {
                    throw new \RuntimeException("Tipo de novedad '{$rowParsed['type']}' no válido.");
                }

                $ruleError = $this->validateRowRuleAndAmount($rowParsed, $service);
                if ($ruleError) {
                    throw new \RuntimeException($ruleError);
                }

                $rule = $this->buildRuleArray($rowParsed);
                $effectivePeriod = $this->parseEffectivePeriod($rowParsed['effective_period'] ?? null);

                $novedad = new BillingNovedad();
                $novedad->service_id = $service->id;
                $novedad->customer_id = $service->customer_id;
                $novedad->type = $rowParsed['type'];
                $novedad->description = $rowParsed['description'] ?? $this->getDefaultDescription($rowParsed['type']);
                $novedad->rule = $rule;
                $novedad->effective_period = $effectivePeriod;
                $novedad->applied = false;
                $novedad->created_by = Auth::id() ?? 1;

                if (isset($rowParsed['amount']) && is_numeric($rowParsed['amount']) && (float)$rowParsed['amount'] != 0) {
                    $novedad->amount = (float)$rowParsed['amount'];
                } else {
                    $registry = app(NovedadCalculatorRegistry::class);
                    $calculator = $registry->for($novedad->type);
                    $novedad->amount = $calculator->calculate($novedad, $service);
                }

                $novedad->save();
                $created++;
                $createdRecords[] = [
                    'row' => $rowNumber,
                    'name' => "{$clientName} ({$novedad->type})",
                    'document' => "Servicio #{$service->id} - Monto: {$novedad->amount}"
                ];

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $clientName,
                    'error' => $e->getMessage()
                ];
                Log::error('Error importando novedad en fila', ['row' => $rowNumber, 'error' => $e->getMessage()]);
            }
        }

        fclose($handle);

        $summary = "Importación de novedades completada. Creados: {$created}, Omitidos: {$skipped}.";

        return [
            'success' => empty($errors),
            'message' => $summary,
            'stats' => [
                'created' => $created,
                'updated' => 0,
                'skipped' => $skipped,
                'total' => $rowNumber - 1,
            ],
            'created_records' => $createdRecords,
            'updated_records' => [],
            'errors' => $errors,
        ];
    }

    private function parseRowData(array $data): array
    {
        $res = [];
        foreach ($data as $k => $v) {
            $cleanKey = str_replace(['novedad.', 'service.'], '', $k);
            $res[$cleanKey] = $v;
        }
        return $res;
    }

    private function validateRowRuleAndAmount(array $row, Service $service): ?string
    {
        $type = $row['type'];
        $amount = isset($row['amount']) ? (float)$row['amount'] : null;

        if ($type === BillingNovedad::T_DESCUENTO_PROMO) {
            $discType = strtolower($row['discount_type'] ?? 'percentage');
            if (!in_array($discType, ['percentage', 'fixed'], true)) {
                return "[Descuento Promocional] discount_type debe ser 'percentage' o 'fixed'.";
            }
            $discVal = isset($row['discount_value']) ? (float)$row['discount_value'] : null;
            if ($discVal === null || $discVal <= 0) {
                return "[Descuento Promocional] Se requiere discount_value mayor a 0 (ej. 15 para 15% o 20000 para monto fijo).";
            }
        } elseif ($type === BillingNovedad::T_PRORRATEO_INI) {
            $startDay = isset($row['start_day']) ? (int)$row['start_day'] : null;
            if ($startDay === null || $startDay < 1 || $startDay > 31) {
                return "[Prorrateo Inicial] Se requiere start_day entre 1 y 31.";
            }
        } elseif ($type === BillingNovedad::T_PRORRATEO_FIN) {
            $endDay = isset($row['end_day']) ? (int)$row['end_day'] : null;
            if ($endDay === null || $endDay < 1 || $endDay > 31) {
                return "[Prorrateo Cancelación] Se requiere end_day entre 1 y 31.";
            }
        } elseif ($type === BillingNovedad::T_MORA) {
            $moraType = strtolower($row['mora_type'] ?? 'fixed');
            if (!in_array($moraType, ['fixed', 'percentage', 'daily_interest'], true)) {
                return "[Mora] mora_type debe ser 'fixed', 'percentage' o 'daily_interest'.";
            }
            $moraVal = isset($row['mora_value']) ? (float)$row['mora_value'] : $amount;
            if ($moraVal === null || $moraVal <= 0) {
                return "[Mora] Se requiere mora_value o amount mayor a 0.";
            }
        } else {
            // Types that require direct explicit amount or price
            if ($amount === null && !in_array($type, [BillingNovedad::T_CAMBIO_PLAN, BillingNovedad::T_ENTREGA_PRODUCTO], true)) {
                return "[{$type}] Se requiere el campo amount o un valor numérico.";
            }
        }

        return null;
    }

    private function buildRuleArray(array $row): ?array
    {
        $type = $row['type'];

        if ($type === BillingNovedad::T_DESCUENTO_PROMO) {
            return [
                'discount_type' => strtolower($row['discount_type'] ?? 'percentage'),
                'discount_value' => (float)($row['discount_value'] ?? 0),
            ];
        }

        if ($type === BillingNovedad::T_PRORRATEO_INI) {
            return [
                'start_day' => (int)($row['start_day'] ?? 1),
            ];
        }

        if ($type === BillingNovedad::T_PRORRATEO_FIN) {
            return [
                'end_day' => (int)($row['end_day'] ?? 30),
            ];
        }

        if ($type === BillingNovedad::T_MORA) {
            return [
                'mora_type' => strtolower($row['mora_type'] ?? 'fixed'),
                'mora_value' => (float)($row['mora_value'] ?? $row['amount'] ?? 0),
                'pending_amount' => (float)($row['amount'] ?? 50000),
            ];
        }

        return null;
    }

    private function parseEffectivePeriod(?string $val): string
    {
        if (!empty($val)) {
            try {
                return Carbon::parse($val)->startOfMonth()->format('Y-m-d');
            } catch (\Throwable $e) {
                // fallback to current date
            }
        }
        return now()->startOfMonth()->format('Y-m-d');
    }

    private function getDefaultDescription(string $type): string
    {
        $labels = [
            BillingNovedad::T_SALDO_FAVOR => 'Saldo a Favor',
            BillingNovedad::T_CARGO_ADICIONAL => 'Cargo Adicional',
            BillingNovedad::T_PRORRATEO_INI => 'Prorrateo Inicial',
            BillingNovedad::T_PRORRATEO_FIN => 'Prorrateo Cancelación',
            BillingNovedad::T_CAMBIO_PLAN => 'Cambio de Plan',
            BillingNovedad::T_DESCUENTO_PROMO => 'Descuento Promocional',
            BillingNovedad::T_CARGO_RECONEXION => 'Cargo por Reconexión',
            BillingNovedad::T_MORA => 'Cargo por Mora',
            BillingNovedad::T_NOTA_CREDITO => 'Nota Crédito',
            BillingNovedad::T_COMPENSACION => 'Compensación de Servicio',
            BillingNovedad::T_EXCESO_CONSUMO => 'Cargo por Exceso de Consumo',
            BillingNovedad::T_IMPUESTO => 'Impuesto Adicional',
            BillingNovedad::T_ENTREGA_PRODUCTO => 'Entrega de Producto',
        ];

        return $labels[$type] ?? 'Novedad de facturación';
    }
}
