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
                ];
            }

            // Verificar servicios activos
            $activeServices = $customer->activeServices()->count();
            if ($activeServices === 0) {
                return [
                    'success' => false,
                    'message' => __('El cliente no tiene servicios activos. Verifique que el cliente tenga servicios con estado diferente a "free" o "inactive".'),
                    'invoice' => null,
                ];
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

            // Generar la factura
            $serviceBuildInvoice = new CustomerBillingService();
            $invoice = $serviceBuildInvoice->generateForPeriod($customer, now());

            if (!$invoice) {
                Log::warning('No se generó factura para el cliente', [
                    'customer_id' => $customerId,
                    'active_services_count' => $activeServices,
                    'customer_status' => $customer->customer_status,
                ]);

                return [
                    'success' => false,
                    'message' => __('No se pudo generar la factura para el cliente. Puede que ya exista una factura para este período o que no haya servicios facturables.'),
                    'invoice' => null,
                ];
            }

            // Si se especificó un servicio, asociarlo a la factura
            if ($serviceId) {
                $service = Service::find($serviceId);
                if ($service && empty($invoice->service_id)) {
                    $invoice->service()->associate($service);
                    if ($service->router_id && empty($invoice->router_id)) {
                        $invoice->router_id = $service->router_id;
                    }
                    $invoice->save();
                }
            }

            // Disparar evento de finalización
            event(new FinalizeInvoice($invoice));

            return [
                'success' => true,
                'message' => __('Factura generada exitosamente.'),
                'invoice' => $invoice,
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
            ];
        }
    }
}
