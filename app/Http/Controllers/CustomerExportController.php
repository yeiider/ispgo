<?php

namespace App\Http\Controllers;

use App\Models\Customers\Customer;
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
}
