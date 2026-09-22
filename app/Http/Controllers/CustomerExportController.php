<?php

namespace App\Http\Controllers;

use App\Models\Customers\Customer;
use App\Models\Services\Service;
use Illuminate\Support\Facades\Response;

class CustomerExportController extends Controller
{
    public function exportCsv()
    {
        // Obtenemos todos los clientes con sus relaciones
        $customers = Customer::with(['addresses', 'taxDetails'])->get();

        // Definimos el nombre del archivo CSV
        $fileName = 'customers_export_' . now()->format('Y_m_d_H_i_s') . '.csv';

        // Creamos un array con los encabezados del archivo CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        // Creamos el contenido del archivo CSV
        $callback = function () use ($customers) {
            // Creamos el apuntador para el archivo
            $file = fopen('php://output', 'w');

            // Agregamos la cabecera del archivo CSV
            fputcsv($file, [
                'Customer ID',
                'Full Name',
                'Email Address',
                'Phone Number',
                'Address Type',
                'Address',
                'City',
                'State/Province',
                'Postal Code',
                'Country',
                'Tax Identification Type',
                'Tax Identification Number',
                'Taxpayer Type',
                'Fiscal Regime',
                'Business Name',
            ]);

            // Iteramos sobre los clientes y escribimos su información en cada fila
            foreach ($customers as $customer) {
                // Recorremos cada una de las direcciones del cliente
                foreach ($customer->addresses as $address) {
                    fputcsv($file, [
                        $customer->id,
                        $customer->full_name,
                        $customer->email_address,
                        $customer->phone_number,
                        $address->address_type,
                        $address->address,
                        $address->city,
                        $address->state_province,
                        $address->postal_code,
                        $address->country,
                        $customer->taxDetails->tax_identification_type ?? '',
                        $customer->taxDetails->tax_identification_number ?? '',
                        $customer->taxDetails->taxpayer_type ?? '',
                        $customer->taxDetails->fiscal_regime ?? '',
                        $customer->taxDetails->business_name ?? '',
                    ]);
                }

                // Si no tiene direcciones, colocamos una fila con datos incompletos para reflejar el cliente
                if ($customer->addresses->isEmpty()) {
                    fputcsv($file, [
                        $customer->id,
                        $customer->full_name,
                        $customer->email_address,
                        $customer->phone_number,
                        '', '', '', '', '', '', // Columnas relacionadas con Address
                        $customer->taxDetails->tax_identification_type ?? '',
                        $customer->taxDetails->tax_identification_number ?? '',
                        $customer->taxDetails->taxpayer_type ?? '',
                        $customer->taxDetails->fiscal_regime ?? '',
                        $customer->taxDetails->business_name ?? '',
                    ]);
                }
            }

            // Cerramos el archivo
            fclose($file);
        };

        // Retornamos la respuesta con el archivo descargable
        return Response::stream($callback, 200, $headers);
    }

    public function exportServiceCsv()
    {
        // Obtenemos todos los servicios con la relación de planes adicionales
        $services = Service::with(['additionalPlans'])->get();

        // Definimos el nombre del archivo CSV
        $fileName = 'services_export_' . now()->format('Y_m_d_H_i_s') . '.csv';

        // Definimos los encabezados del CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        // Generamos el contenido del CSV
        $callback = function () use ($services) {
            $file = fopen('php://output', 'w');

            // Escribimos los encabezados en el archivo CSV compatibles con ServiceImporterService
            fputcsv($file, [
                'id',
                'sn',
                'service_ip',
                'mac_address',
                'service_status',
                'service_type',
                'plan_id',
                'additional_plans',
                'router_id',
                'billing_cycle_id',
                'activation_date',
                'installation_date',
                'service_notes',
                'username_router',
                'password_router',
                'unu_latitude',
                'unu_longitude',
            ]);

            foreach ($services as $service) {
                $apIds = $service->additionalPlans->pluck('id')->toArray();
                $additionalPlansStr = empty($apIds)
                    ? ''
                    : (count($apIds) === 1 ? (string) $apIds[0] : '[' . implode(', ', $apIds) . ']');

                $actDate = $service->activation_date
                    ? (is_string($service->activation_date) ? $service->activation_date : $service->activation_date->format('Y-m-d'))
                    : '';
                $instDate = $service->installation_date
                    ? (is_string($service->installation_date) ? $service->installation_date : $service->installation_date->format('Y-m-d'))
                    : '';

                fputcsv($file, [
                    $service->id,
                    $service->sn,
                    $service->service_ip,
                    $service->mac_address,
                    $service->service_status,
                    $service->service_type,
                    $service->plan_id,
                    $additionalPlansStr,
                    $service->router_id,
                    $service->billing_cycle_id,
                    $actDate,
                    $instDate,
                    $service->service_notes,
                    $service->username_router,
                    $service->password_router,
                    $service->unu_latitude,
                    $service->unu_longitude,
                ]);
            }

            fclose($file);
        };

        // Retornamos la respuesta para descargar el archivo
        return Response::stream($callback, 200, $headers);
    }
}
