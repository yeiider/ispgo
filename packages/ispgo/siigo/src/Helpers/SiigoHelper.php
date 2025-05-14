<?php

namespace Ispgo\Siigo\Helpers;

use App\Models\Customers\Customer;

class SiigoHelper
{

    public static function buildPayload(Customer $customer): array
    {
        $address = $customer->addresses()->first(); // Primera dirección asociada
        $taxDetails = $customer->taxDetails; // Detalles fiscales
        $phone = $customer->phone_number; // Número de teléfono principal

        return [
            "type" => "Customer",
            "person_type" => $taxDetails ? $taxDetails->taxpayer_type : "Person", // Tipo de persona
            "id_type" => $customer->document_type === 'NIT' ? '13' : '31', // Tipo de documento
            "identification" => substr($taxDetails->tax_identification_number, 0, strpos($taxDetails->tax_identification_number, '-')),
            "check_digit" => substr($taxDetails->tax_identification_number, strpos($taxDetails->tax_identification_number, '-') + 1),
            "name" => [
                $customer->first_name,
                $customer->last_name
            ],
            "commercial_name" => $taxDetails->business_name, // Nombre comercial
            "branch_office" => 0, // Establecimiento
            "active" => true, // Cliente activo
            "vat_responsible" => $taxDetails && $taxDetails->fiscal_regime === 'Responsible',
            "fiscal_responsibilities" => [
                [
                    "code" => $taxDetails ? $taxDetails->fiscal_regime : "R-99-PN" // Responsabilidad fiscal
                ]
            ],
            "address" => [
                "address" => $address->address,
                "city" => [
                    "country_code" => $address->country ?? "CO",
                    "state_code" => $address->state_province,
                    "city_code" => $address->city
                ],
                "postal_code" => $address->postal_code
            ],
            "phones" => [
                [
                    "indicative" => "57", // Indicativo del país
                    "number" => $phone,
                    "extension" => null // Si hay extensiones
                ]
            ],
            "contacts" => [
                [
                    "first_name" => $customer->first_name,
                    "last_name" => $customer->last_name,
                    "email" => $customer->email_address,
                    "phone" => [
                        "indicative" => "57",
                        "number" => $phone,
                        "extension" => null
                    ]
                ]
            ]
        ];
    }

}
