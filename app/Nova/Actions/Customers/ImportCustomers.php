<?php

namespace App\Nova\Actions\Customers;

use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Services\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;

class ImportCustomers extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Importar Clientes (CSV)';

    public $standalone = true;

    public function fields(\Laravel\Nova\Http\Requests\NovaRequest $request): array
    {
        return [
            File::make('Archivo CSV', 'csv_file')
                ->acceptedTypes('.csv,text/csv')
                ->rules('required', 'file')
                ->help('Cargue un archivo CSV. Vea /samples/customers_import_example.csv para el formato.'),
            Select::make('Modo de importación', 'mode')
                ->options([
                    'create_only' => 'Crear solamente (si existe, omitir)',
                    'update_only' => 'Actualizar solamente (si no existe, omitir)',
                    'create_or_update' => 'Crear o actualizar (recomendado)'
                ])->rules('required')->displayUsingLabels()
        ];
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        $file = $fields->csv_file;
        $mode = $fields->mode ?? 'create_or_update';

        if (!$file || !file_exists($file->getRealPath())) {
            return Action::danger('Archivo no válido.');
        }

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return Action::danger('No se pudo abrir el archivo.');
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            return Action::danger('El archivo CSV está vacío.');
        }
        $headers = array_map(function ($h) { return trim(mb_strtolower($h)); }, $headers);

        $rowNumber = 1; // including header line
        $created = 0; $updated = 0; $skipped = 0; $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if (count($row) === 1 && trim($row[0]) === '') { continue; }
            $data = [];
            foreach ($headers as $i => $key) {
                $data[$key] = $row[$i] ?? null;
            }
            try {
                DB::beginTransaction();

                // Split data by prefixes: customer., address., service.
                [$customerData, $addressData, $serviceData] = $this->splitData($data);

                // Track if any update occurred in this row
                $rowUpdated = false;

                // Basic normalization
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
                    // Validate minimal required for create
                    $validator = Validator::make($customerData, [
                        'first_name' => 'required|max:100',
                        'last_name' => 'required|max:100',
                        'email_address' => 'required|email|max:100',
                        'document_type' => 'required|max:20',
                        'identity_document' => 'required|max:12',
                        'customer_status' => 'required|in:active,inactive',
                    ]);
                    if ($validator->fails()) {
                        throw new \RuntimeException('Errores de validación (customer): ' . $validator->errors()->toJson());
                    }
                    $customer = Customer::create($customerData);
                    $created++;
                } else {
                    if ($mode === 'create_only') {
                        $skipped++;
                        DB::rollBack();
                        continue;
                    }
                    // Update only the provided customer fields
                    $customer->fill($customerData);
                    if ($customer->isDirty()) {
                        $customer->save();
                        $rowUpdated = true;
                    }
                }

                // Address handling: if any address fields provided, create or update
                if (!empty($addressData)) {
                    $addressId = $addressData['id'] ?? null;
                    $addressModel = null;
                    
                    // First try to find by ID if provided
                    if ($addressId) {
                        $addressModel = Address::where('customer_id', $customer->id)->where('id', $addressId)->first();
                    }
                    
                    // If not found by ID, check if customer already has an address
                    if (!$addressModel) {
                        $addressModel = Address::where('customer_id', $customer->id)->first();
                    }
                    
                    if ($addressModel) {
                        // Update existing address
                        $addressModel->fill($addressData);
                        $addressModel->customer_id = $customer->id;
                        if ($addressModel->isDirty()) {
                            $addressModel->save();
                            $rowUpdated = true;
                        }
                    } else {
                        // Create new address - minimal required validation
                        $addrValidator = Validator::make($addressData, [
                            'address' => 'required|max:100',
                            'city' => 'required|max:100',
                            'state_province' => 'required|max:100',
                            'postal_code' => 'required|max:20',
                            'country' => 'required|max:100',
                            'address_type' => 'required|in:billing,shipping',
                        ]);
                        if ($addrValidator->fails()) {
                            // Allow missing address block when updating only
                            if ($mode === 'update_only') {
                                // ignore address errors in update-only if not all fields
                            } else {
                                throw new \RuntimeException('Errores de validación (address): ' . $addrValidator->errors()->toJson());
                            }
                        } else {
                            $addressModel = new Address($addressData);
                            $addressModel->customer_id = $customer->id;
                            $addressModel->save();
                        }
                    }
                }

                // Service handling: if any service fields provided, create or update
                if (!empty($serviceData)) {
                    $serviceId = $serviceData['id'] ?? null;
                    $serviceModel = null;
                    
                    // First try to find by ID if provided
                    if ($serviceId) {
                        $serviceModel = Service::where('customer_id', $customer->id)->where('id', $serviceId)->first();
                    }
                    
                    // If not found by ID, check if customer already has a service
                    if (!$serviceModel) {
                        $serviceModel = Service::where('customer_id', $customer->id)->first();
                    }

                    // If creating and service_location not provided, try to use first address
                    if (!isset($serviceData['service_location'])) {
                        $firstAddress = $customer->addresses()->first();
                        if ($firstAddress) {
                            $serviceData['service_location'] = $firstAddress->id;
                        }
                    }

                    if ($serviceModel) {
                        // Update existing service
                        $serviceModel->fill($serviceData);
                        $serviceModel->customer_id = $customer->id;
                        if ($serviceModel->isDirty()) {
                            $serviceModel->save();
                            $rowUpdated = true;
                        }
                    } else {
                        // Create new service - minimal required validation
                        $svcValidator = Validator::make($serviceData, [
                            'router_id' => 'required|exists:routers,id',
                            'service_ip' => 'required|ip',
                            'service_status' => 'required|in:active,inactive,suspended,pending,free',
                            'plan_id' => 'required|exists:plans,id',
                        ]);
                        if ($svcValidator->fails()) {
                            if ($mode === 'update_only') {
                                // ignore service errors in update-only mode
                            } else {
                                throw new \RuntimeException('Errores de validación (service): ' . $svcValidator->errors()->toJson());
                            }
                        } else {
                            $serviceModel = new Service($serviceData);
                            $serviceModel->customer_id = $customer->id;
                            $serviceModel->save();
                        }
                    }
                }

                // If something was updated but not counted yet, count it now
                if ($rowUpdated) {
                    $updated++;
                } else if (!$rowUpdated && $customer->wasRecentlyCreated === false && $created === 0) {
                    // Nothing was updated or created in this iteration
                    $skipped++;
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = "Fila {$rowNumber}: " . $e->getMessage();
                Log::error('Error importando clientes', ['row' => $rowNumber, 'error' => $e->getMessage()]);
            }
        }
        fclose($handle);

        $summary = "Importación completada. Creados: {$created}, Actualizados: {$updated}, Omitidos: {$skipped}.";
        if (!empty($errors)) {
            $summary .= " Errores: " . min(5, count($errors)) . " mostrados.\n" . implode("\n", array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $summary .= "\n... y " . (count($errors) - 5) . " más.";
            }
        }

        return Action::message($summary);
    }

    private function splitData(array $data): array
    {
        $customer = [];
        $address = [];
        $service = [];
        foreach ($data as $key => $value) {
            if (strpos($key, 'customer.') === 0) {
                $customer[substr($key, 9)] = $this->nullIfEmpty($value);
            } elseif (strpos($key, 'address.') === 0) {
                $address[substr($key, 8)] = $this->nullIfEmpty($value);
            } elseif (strpos($key, 'service.') === 0) {
                $service[substr($key, 8)] = $this->nullIfEmpty($value);
            }
        }
        // defaults for customer
        if (!isset($customer['customer_status']) || empty($customer['customer_status'])) {
            $customer['customer_status'] = 'active';
        }
        return [$customer, $address, $service];
    }

    private function nullIfEmpty($value)
    {
        $v = is_string($value) ? trim($value) : $value;
        return ($v === '' || $v === null) ? null : $v;
    }
}
