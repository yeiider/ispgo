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
        // Obtenemos todos los servicios con sus relaciones (ajusta las relaciones si es necesario)
        $services = Service::with([])->get(); // Agregar relaciones necesarias en el array

        // Definimos el nombre del archivo CSV
        $fileName = 'services_export_' . now()->format('Y_m_d_H_i_s') . '.csv';

        // Definimos los encabezados del CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        // Generamos el contenido del CSV
        $callback = function () use ($services) {
            // Abrimos un archivo para escritura en memoria temporal
            $file = fopen('php://output', 'w');

            // Escribimos los encabezados en el archivo CSV
            fputcsv($file, [
                'Router ID',
                'Customer ID',
                'Internet Plan ID',
                'Service IP',
                'Plan ID',
                'Username Router',
                'Password Router',
                'Service Status',
                'Activation Date',
                'Deactivation Date',
                'Bandwidth',
                'MAC Address',
                'Installation Date',
                'Service Notes',
                'Contract ID',
                'Support Contact',
                'Service Location',
                'Service Type',
                'Static IP',
                'Data Limit',
                'Last Maintenance',
                'Billing Cycle',
                'Service Priority',
                'SN',
                'Assigned Technician',
                'Service Contract',
                'Created By',
                'Updated By',
            ]);

            // Iteramos sobre los servicios para escribir cada registro
            foreach ($services as $service) {
                fputcsv($file, [
                    $service->router_id,
                    $service->customer_id,
                    $service->internet_plan_id,
                    $service->service_ip,
                    $service->plan_id,
                    $service->username_router,
                    $service->password_router,
                    $service->service_status,
                    $service->activation_date,
                    $service->deactivation_date,
                    $service->bandwidth,
                    $service->mac_address,
                    $service->installation_date,
                    $service->service_notes,
                    $service->contract_id,
                    $service->support_contact,
                    $service->service_location,
                    $service->service_type,
                    $service->static_ip,
                    $service->data_limit,
                    $service->last_maintenance,
                    $service->billing_cycle,
                    $service->service_priority,
                    $service->sn,
                    $service->assigned_technician,
                    $service->service_contract,
                    $service->created_by,
                    $service->updated_by,
                ]);
            }

            // Cerramos el archivo
            fclose($file);
        };

        // Retornamos la respuesta para descargar el archivo
        return Response::stream($callback, 200, $headers);
    }

}
