<?php

namespace App\Services\Customers;

use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Customers\TaxDetail;
use App\Models\Services\Service;
use App\Models\Router;
use App\Models\Plans\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerImporterService
{
    /**
     * Phase 1: Dry-run CSV validation (Does not save permanent data)
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

        // Run validation inside a transaction that is ALWAYS rolled back
        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                if (count($row) === 1 && trim($row[0]) === '') {
                    continue;
                }

                $data = [];
                foreach ($headers as $i => $key) {
                    $data[$key] = $row[$i] ?? null;
                }

                [$customerData, $addressData, $serviceData, $taxData] = $this->splitData($data);

                $clientName = trim(($customerData['first_name'] ?? '') . ' ' . ($customerData['last_name'] ?? ''));
                if (empty($clientName)) {
                    $clientName = "Fila {$rowNumber}";
                }

                $identity = $customerData['identity_document'] ?? null;
                if (!$identity) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'name' => $clientName,
                        'error' => 'Falta el documento de identidad obligatorio (customer.identity_document).'
                    ];
                    continue;
                }

                $customer = Customer::where('identity_document', $identity)->first();

                if (!$customer) {
                    if ($mode === 'update_only') {
                        $skipped++;
                        $skippedRecords[] = ['row' => $rowNumber, 'name' => $clientName, 'document' => $identity, 'reason' => 'No existe en sistema (modo actualizar solamente)'];
                        continue;
                    }

                    // Validate customer required fields
                    $validator = Validator::make($customerData, [
                        'first_name' => 'required|max:100',
                        'last_name' => 'required|max:100',
                        'email_address' => 'required|email|max:100',
                        'document_type' => 'required|max:20',
                        'identity_document' => 'required|max:12',
                        'customer_status' => 'required|in:active,inactive',
                        'router_id' => 'required|exists:routers,id',
                    ]);

                    if ($validator->fails()) {
                        $msg = $this->formatValidationErrors('Cliente', $validator->errors());
                        $errors[] = ['row' => $rowNumber, 'name' => $clientName, 'error' => $msg];
                        continue;
                    }

                    // Check address validation
                    if (!empty($addressData)) {
                        $addrValidator = Validator::make($addressData, [
                            'address' => 'required|max:100',
                            'city' => 'required|max:100',
                            'state_province' => 'required|max:100',
                            'postal_code' => 'required|max:20',
                            'country' => 'required|max:100',
                            'address_type' => 'required|in:billing,shipping',
                        ]);
                        if ($addrValidator->fails()) {
                            $msg = $this->formatValidationErrors('Dirección', $addrValidator->errors());
                            $errors[] = ['row' => $rowNumber, 'name' => $clientName, 'error' => $msg];
                            continue;
                        }
                    }

                    // Check tax validation
                    if (!empty($taxData) && isset($taxData['tax_identification_number']) && trim($taxData['tax_identification_number']) !== '') {
                        $existingTax = TaxDetail::where('tax_identification_number', $taxData['tax_identification_number'])->first();
                        if ($existingTax) {
                            $errors[] = [
                                'row' => $rowNumber,
                                'name' => $clientName,
                                'error' => "El NIT/Identificación fiscal {$taxData['tax_identification_number']} ya pertenece a otro cliente registrado."
                            ];
                            continue;
                        }
                    }

                    // Check service validation
                    if (!empty($serviceData)) {
                        $svcValidator = Validator::make($serviceData, [
                            'router_id' => 'required|exists:routers,id',
                            'service_ip' => 'required|ip',
                            'service_status' => 'required|in:active,inactive,suspended,pending,free',
                            'plan_id' => 'required|exists:plans,id',
                        ]);
                        if ($svcValidator->fails()) {
                            $msg = $this->formatValidationErrors('Servicio', $svcValidator->errors());
                            $errors[] = ['row' => $rowNumber, 'name' => $clientName, 'error' => $msg];
                            continue;
                        }
                    }

                    $created++;
                    $createdRecords[] = ['row' => $rowNumber, 'name' => $clientName, 'document' => $identity];
                } else {
                    if ($mode === 'create_only') {
                        $skipped++;
                        $skippedRecords[] = ['row' => $rowNumber, 'name' => $clientName, 'document' => $identity, 'reason' => 'Ya existe en sistema (modo crear solamente)'];
                        continue;
                    }

                    $updated++;
                    $updatedRecords[] = ['row' => $rowNumber, 'name' => $clientName, 'document' => $identity];
                }
            }
        } finally {
            fclose($handle);
            DB::rollBack(); // Always roll back validation transaction
        }

        $isValid = empty($errors);

        return [
            'valid' => $isValid,
            'message' => $isValid
                ? "Verificación exitosa. Se crearán {$created}, actualizarán {$updated} y omitirán {$skipped} registros."
                : "Se encontraron " . count($errors) . " errores de validación. Por favor corrija el archivo antes de proceder.",
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
     * Phase 2: Execute actual CSV Import (Atomic row processing)
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
            return trim(mb_strtolower($h));
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
                $data[$key] = $row[$i] ?? null;
            }

            try {
                DB::beginTransaction();

                [$customerData, $addressData, $serviceData, $taxData] = $this->splitData($data);
                $rowUpdated = false;

                $clientName = trim(($customerData['first_name'] ?? '') . ' ' . ($customerData['last_name'] ?? ''));
                $identity = $customerData['identity_document'] ?? null;

                if (!$identity) {
                    throw new \RuntimeException('Falta customer.identity_document');
                }

                $customer = Customer::where('identity_document', $identity)->first();

                if (!$customer) {
                    if ($mode === 'update_only') {
                        $skipped++;
                        DB::rollBack();
                        continue;
                    }

                    $validator = Validator::make($customerData, [
                        'first_name' => 'required|max:100',
                        'last_name' => 'required|max:100',
                        'email_address' => 'required|email|max:100',
                        'document_type' => 'required|max:20',
                        'identity_document' => 'required|max:12',
                        'customer_status' => 'required|in:active,inactive',
                        'router_id' => 'required|exists:routers,id',
                    ]);

                    if ($validator->fails()) {
                        throw new \RuntimeException($this->formatValidationErrors('Cliente', $validator->errors()));
                    }

                    $customer = Customer::create($customerData);
                    $created++;
                    $createdRecords[] = ['row' => $rowNumber, 'name' => $clientName, 'document' => $identity];
                } else {
                    if ($mode === 'create_only') {
                        $skipped++;
                        DB::rollBack();
                        continue;
                    }

                    $customer->fill($customerData);
                    if ($customer->isDirty()) {
                        $customer->save();
                        $rowUpdated = true;
                    }
                }

                // Address handling
                if (!empty($addressData)) {
                    $addressId = $addressData['id'] ?? null;
                    $addressModel = null;

                    if ($addressId) {
                        $addressModel = Address::where('customer_id', $customer->id)->where('id', $addressId)->first();
                    }

                    if (!$addressModel) {
                        $addressModel = Address::where('customer_id', $customer->id)->first();
                    }

                    if ($addressModel) {
                        $addressModel->fill($addressData);
                        $addressModel->customer_id = $customer->id;
                        if ($addressModel->isDirty()) {
                            $addressModel->save();
                            $rowUpdated = true;
                        }
                    } else {
                        $addrValidator = Validator::make($addressData, [
                            'address' => 'required|max:100',
                            'city' => 'required|max:100',
                            'state_province' => 'required|max:100',
                            'postal_code' => 'required|max:20',
                            'country' => 'required|max:100',
                            'address_type' => 'required|in:billing,shipping',
                        ]);

                        if ($addrValidator->fails()) {
                            if ($mode !== 'update_only') {
                                throw new \RuntimeException($this->formatValidationErrors('Dirección', $addrValidator->errors()));
                            }
                        } else {
                            $addressModel = new Address($addressData);
                            $addressModel->customer_id = $customer->id;
                            $addressModel->save();
                        }
                    }
                }

                // TaxDetail handling
                if (!empty($taxData) && isset($taxData['tax_identification_number']) && trim($taxData['tax_identification_number']) !== '') {
                    $taxModel = TaxDetail::where('customer_id', $customer->id)->first();

                    foreach (['enable_billing', 'send_notifications', 'send_invoice'] as $boolField) {
                        if (isset($taxData[$boolField])) {
                            $val = strtolower(trim($taxData[$boolField]));
                            $taxData[$boolField] = in_array($val, ['1', 'true', 'yes', 'si', 'sí', 'on', 'active']) ? 1 : 0;
                        } else {
                            $taxData[$boolField] = ($boolField === 'enable_billing') ? 1 : 0;
                        }
                    }

                    if (!isset($taxData['tax_identification_type']) || trim($taxData['tax_identification_type']) === '') {
                        $taxData['tax_identification_type'] = 'NIT';
                    }
                    if (!isset($taxData['taxpayer_type']) || trim($taxData['taxpayer_type']) === '') {
                        $taxData['taxpayer_type'] = 'personas_naturales';
                    }
                    if (!isset($taxData['fiscal_regime']) || trim($taxData['fiscal_regime']) === '') {
                        $taxData['fiscal_regime'] = 'simplified';
                    }
                    if (!isset($taxData['business_name']) || trim($taxData['business_name']) === '') {
                        $taxData['business_name'] = ucwords(($customerData['first_name'] ?? $customer->first_name) . ' ' . ($customerData['last_name'] ?? $customer->last_name));
                    }

                    if ($taxModel) {
                        $taxModel->fill($taxData);
                        if ($taxModel->isDirty()) {
                            $taxModel->save();
                            $rowUpdated = true;
                        }
                    } else {
                        $existingTax = TaxDetail::where('tax_identification_number', $taxData['tax_identification_number'])->first();
                        if ($existingTax) {
                            throw new \RuntimeException("El NIT/Identificación fiscal {$taxData['tax_identification_number']} ya pertenece a otro cliente.");
                        }

                        $taxModel = new TaxDetail($taxData);
                        $taxModel->customer_id = $customer->id;
                        $taxModel->save();
                        $rowUpdated = true;
                    }
                }

                // Service handling
                if (!empty($serviceData)) {
                    $serviceId = $serviceData['id'] ?? null;
                    $serviceModel = null;

                    if ($serviceId) {
                        $serviceModel = Service::withoutGlobalScopes()->where('customer_id', $customer->id)->where('id', $serviceId)->first();
                    }

                    if (!$serviceModel) {
                        $serviceModel = Service::withoutGlobalScopes()->where('customer_id', $customer->id)->first();
                    }

                    if (!isset($serviceData['service_location'])) {
                        $firstAddress = $customer->addresses()->first();
                        if ($firstAddress) {
                            $serviceData['service_location'] = $firstAddress->id;
                        }
                    }

                    if ($serviceModel) {
                        $serviceModel->fill($serviceData);
                        $serviceModel->customer_id = $customer->id;
                        if ($serviceModel->isDirty()) {
                            $serviceModel->saveQuietly();
                            $rowUpdated = true;
                        }
                    } else {
                        $svcValidator = Validator::make($serviceData, [
                            'router_id' => 'required|exists:routers,id',
                            'service_ip' => 'required|ip',
                            'service_status' => 'required|in:active,inactive,suspended,pending,free',
                            'plan_id' => 'required|exists:plans,id',
                        ]);

                        if ($svcValidator->fails()) {
                            if ($mode !== 'update_only') {
                                throw new \RuntimeException($this->formatValidationErrors('Servicio', $svcValidator->errors()));
                            }
                        } else {
                            $serviceModel = new Service($serviceData);
                            $serviceModel->customer_id = $customer->id;
                            $serviceModel->save();
                        }
                    }
                }

                if ($rowUpdated && $customer->wasRecentlyCreated === false) {
                    $updated++;
                    $updatedRecords[] = ['row' => $rowNumber, 'name' => $clientName, 'document' => $identity];
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack(); // Complete row rollback ensures no orphan customer/data!
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $clientName ?? "Fila {$rowNumber}",
                    'error' => $e->getMessage()
                ];
                Log::error('Error importando cliente en fila', ['row' => $rowNumber, 'error' => $e->getMessage()]);
            }
        }

        fclose($handle);

        $summary = "Importación completada. Creados: {$created}, Actualizados: {$updated}, Omitidos: {$skipped}.";

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

    private function splitData(array $data): array
    {
        $customer = [];
        $address = [];
        $service = [];
        $tax = [];

        foreach ($data as $key => $value) {
            if (strpos($key, 'customer.') === 0) {
                $customer[substr($key, 9)] = $this->nullIfEmpty($value);
            } elseif (strpos($key, 'address.') === 0) {
                $address[substr($key, 8)] = $this->nullIfEmpty($value);
            } elseif (strpos($key, 'service.') === 0) {
                $service[substr($key, 8)] = $this->nullIfEmpty($value);
            } elseif (strpos($key, 'tax.') === 0) {
                $tax[substr($key, 4)] = $this->nullIfEmpty($value);
            }
        }

        if (!isset($customer['customer_status']) || empty($customer['customer_status'])) {
            $customer['customer_status'] = 'active';
        }

        if (!isset($customer['router_id']) || empty($customer['router_id'])) {
            if (isset($service['router_id']) && !empty($service['router_id'])) {
                $customer['router_id'] = $service['router_id'];
            }
        }

        return [$customer, $address, $service, $tax];
    }

    private function nullIfEmpty($value)
    {
        $v = is_string($value) ? trim($value) : $value;
        return ($v === '' || $v === null) ? null : $v;
    }

    private function formatValidationErrors(string $section, $validatorErrors): string
    {
        $messages = [];
        $list = [];

        if (is_object($validatorErrors) && method_exists($validatorErrors, 'all')) {
            $list = $validatorErrors->all();
        } elseif (is_array($validatorErrors)) {
            $list = $validatorErrors;
        } else {
            $list = [(string) $validatorErrors];
        }

        foreach ($list as $msg) {
            if (is_array($msg)) {
                $msg = implode(', ', $msg);
            }
            $msg = str_replace(
                ['first_name', 'last_name', 'email_address', 'document_type', 'identity_document', 'router_id', 'plan_id', 'service_ip', 'customer_status'],
                ['Nombre', 'Apellido', 'Email', 'Tipo Documento', 'N° Documento', 'ID Zona (Router)', 'ID Plan', 'IP Servicio', 'Estado Cliente'],
                (string)$msg
            );
            $messages[] = $msg;
        }

        return "[$section] " . implode(', ', $messages);
    }
}
