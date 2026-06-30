<?php

namespace App\GraphQL\Mutations;

use App\Events\FinalizeInvoice;
use App\Models\Customers\Customer;
use App\Models\Services\Service;
use App\Models\User;
use App\Services\Billing\CustomerBillingService;
use App\Settings\GeneralProviderConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GenerateInvoiceMutation
{
    /**
     * Generate an invoice for a customer
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function resolve($_, array $args)
    {
        try {
            $customerId = $args['customer_id'];
            $serviceId = $args['service_id'] ?? null;

            $customer = Customer::find($customerId);

            if (!$customer) {
                return [
                    'success' => false,
                    'message' => __('El cliente no existe.'),
                    'invoice' => null,
                    'invoices' => [],
                ];
            }

            // Verificar servicios activos
            $activeServicesQuery = $customer->activeServices();
            $activeServicesCount = $activeServicesQuery->count();
            if ($activeServicesCount === 0) {
                return [
                    'success' => false,
                    'message' => __('El cliente no tiene servicios activos. Verifique que el cliente tenga servicios con estado diferente a "free" o "inactive".'),
                    'invoice' => null,
                    'invoices' => [],
                ];
            }

            // Si se seleccionó un servicio específico, validar que exista y pertenezca al cliente
            if ($serviceId) {
                $service = Service::find($serviceId);
                if (!$service || (string)$service->customer_id !== (string)$customerId) {
                    return [
                        'success' => false,
                        'message' => __('El servicio seleccionado no existe o no pertenece al cliente.'),
                        'invoice' => null,
                        'invoices' => [],
                    ];
                }
            }

            // Asegurar que hay un usuario autenticado
            // El middleware AttemptAuthentication de Lighthouse ya maneja la autenticación via Bearer token
            // Si no hay usuario autenticado, usar el usuario por defecto como fallback
            if (!Auth::guard('api')->check()) {
                $defaultUserId = GeneralProviderConfig::getDefaultUser();
                if ($defaultUserId) {
                    $defaultUser = User::find($defaultUserId);
                    if ($defaultUser) {
                        Auth::guard('api')->setUser($defaultUser);
                    }
                }
            }

            $serviceBuildInvoice = new CustomerBillingService();

            // ─── Facturación "Por servicios" (per_service) ───
            // Cuando el cliente tiene billing_mode='per_service' y NO se seleccionó
            // un servicio específico ("Todos los servicios activos"), se genera
            // una factura individual por cada servicio activo.
            if ($customer->usesPerServiceBilling() && !$serviceId) {
                $activeServices = $customer->activeServices()->get();
                $generatedInvoices = [];
                $errors = [];

                foreach ($activeServices as $svc) {
                    try {
                        $invoice = $serviceBuildInvoice->generateForPeriod($customer, now(), $svc->id);
                        if ($invoice) {
                            event(new FinalizeInvoice($invoice));
                            $generatedInvoices[] = $invoice;
                        }
                    } catch (\Exception $e) {
                        Log::warning("No se pudo generar factura individual para servicio {$svc->id}", [
                            'customer_id' => $customerId,
                            'service_id' => $svc->id,
                            'error' => $e->getMessage(),
                        ]);
                        $errors[] = $svc->id;
                    }
                }

                if (empty($generatedInvoices)) {
                    return [
                        'success' => false,
                        'message' => __('No se pudo generar ninguna factura para los servicios del cliente.'),
                        'invoice' => null,
                        'invoices' => [],
                    ];
                }

                $count = count($generatedInvoices);
                $msg = $count === 1
                    ? __('Se generó 1 factura individual exitosamente.')
                    : __("Se generaron {$count} facturas individuales exitosamente.");

                if (!empty($errors)) {
                    $msg .= ' ' . __('Algunos servicios no pudieron ser facturados: ') . implode(', ', $errors);
                }

                return [
                    'success' => true,
                    'message' => $msg,
                    'invoice' => $generatedInvoices[0],
                    'invoices' => $generatedInvoices,
                ];
            }

            // ─── Facturación "Total" (default) o servicio específico ───
            $invoice = $serviceBuildInvoice->generateForPeriod($customer, now(), $serviceId ?: null);

            if (!$invoice) {
                Log::warning('No se generó factura para el cliente', [
                    'customer_id' => $customerId,
                    'service_id'  => $serviceId,
                    'active_services_count' => $activeServicesCount,
                    'customer_status' => $customer->customer_status,
                ]);

                return [
                    'success' => false,
                    'message' => __('No se pudo generar la factura para el cliente. Puede que ya exista una factura para este período o que no haya servicios facturables.'),
                    'invoice' => null,
                    'invoices' => [],
                ];
            }

            // Disparar evento de finalización
            event(new FinalizeInvoice($invoice));

            return [
                'success' => true,
                'message' => __('Factura generada exitosamente.'),
                'invoice' => $invoice,
                'invoices' => [$invoice],
            ];

        } catch (\Exception $e) {
            Log::error('Error en GenerateInvoiceMutation', [
                'customer_id' => $args['customer_id'] ?? null,
                'service_id' => $args['service_id'] ?? null,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);

            // Si el mensaje incluye información útil del CustomerBillingService, lo mostramos
            $errorMessage = $e->getMessage();

            return [
                'success' => false,
                'message' => __('Error al generar la factura: :message', ['message' => $errorMessage]),
                'invoice' => null,
                'invoices' => [],
            ];
        }
    }
}
