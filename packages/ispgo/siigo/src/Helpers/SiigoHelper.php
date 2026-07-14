<?php

namespace Ispgo\Siigo\Helpers;

use App\Models\Customers\Customer;

class SiigoHelper
{

    public static function buildPayload(Customer $customer): array
    {
        $addressObj = $customer->addresses()->first();
        $taxDetails = $customer->taxDetails;
        $phone = $customer->phone_number;

        $dbPersonType = $taxDetails ? $taxDetails->taxpayer_type : "Person";
        $personType = "Person";
        if (in_array($dbPersonType, ['personas_juridicas', 'Company', 'regimen_simple', 'regimen_ordinario', 'grandes_contribuyentes'])) {
            $personType = "Company";
        }

        // Map document types to Siigo codes: CC -> 13, NIT -> 31, CE -> 22, PAS -> 41
        $docType = strtoupper($taxDetails ? ($taxDetails->tax_identification_type ?: $customer->document_type) : $customer->document_type);
        $idType = '13'; // Default to Cédula
        if ($docType === 'NIT') {
            $idType = '31';
        } elseif ($docType === 'CE' || $docType === 'CÉDULA DE EXTRANJERÍA') {
            $idType = '22';
        } elseif ($docType === 'PAS' || $docType === 'PASAPORTE' || $docType === 'PP') {
            $idType = '41';
        }

        $identification = self::getCustomerIdentification($customer);

        $checkDigit = null;
        if ($taxDetails && !empty($taxDetails->tax_identification_number)) {
            $idStr = $taxDetails->tax_identification_number;
            if (strpos($idStr, '-') !== false) {
                $checkDigit = substr($idStr, strpos($idStr, '-') + 1);
            }
        }

        $name = [];
        if ($personType === 'Company') {
            $name[] = ($taxDetails && !empty($taxDetails->business_name))
                ? $taxDetails->business_name
                : trim($customer->first_name . ' ' . $customer->last_name);
        } else {
            $name[] = $customer->first_name ?: 'N/A';
            $name[] = $customer->last_name ?: 'N/A';
        }

        $fiscalRegimeCode = "R-99-PN";
        if ($taxDetails && !empty($taxDetails->fiscal_regime)) {
            $regimeRaw = strtolower($taxDetails->fiscal_regime);
            if ($regimeRaw === 'general' || $regimeRaw === 'responsible') {
                $fiscalRegimeCode = "O-13";
            } elseif ($regimeRaw === 'simplified' || $regimeRaw === 'nonresponsible') {
                $fiscalRegimeCode = "R-99-PN";
            } elseif (preg_match('/^[OR]-[0-9]+(-[A-Z]+)?$/', $taxDetails->fiscal_regime)) {
                $fiscalRegimeCode = $taxDetails->fiscal_regime;
            } else {
                $fiscalRegimeCode = $personType === 'Company' ? 'O-99' : 'R-99-PN';
            }
        }

        $addressText = $addressObj ? $addressObj->address : 'Direccion';
        $country = $addressObj ? ($addressObj->country ?? 'CO') : 'CO';

        $mappedCity = self::mapStateAndCity(
            $addressObj ? $addressObj->state_province : null,
            $addressObj ? $addressObj->city : null
        );
        $state = $mappedCity['state_code'];
        $city = $mappedCity['city_code'];
        $postal = $addressObj ? $addressObj->postal_code : '110001';

        // Clean phone number: keep only digits and limit to 10 chars
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone ?: '3000000000');
        if (strlen($cleanPhone) > 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        $payload = [
            "type" => "Customer",
            "person_type" => $personType,
            "id_type" => $idType,
            "identification" => $identification,
            "name" => $name,
            "branch_office" => 0,
            "active" => true,
            "vat_responsible" => $taxDetails && $taxDetails->fiscal_regime === 'Responsible',
            "fiscal_responsibilities" => [
                [
                    "code" => $fiscalRegimeCode
                ]
            ],
            "address" => [
                "address" => $addressText,
                "city" => [
                    "country_code" => $country,
                    "state_code" => $state,
                    "city_code" => $city
                ],
                "postal_code" => $postal
            ],
            "phones" => [
                [
                    "indicative" => "57",
                    "number" => $cleanPhone,
                    "extension" => null
                ]
            ],
            "contacts" => [
                [
                    "first_name" => $customer->first_name ?: 'N/A',
                    "last_name" => $customer->last_name ?: 'N/A',
                    "email" => $customer->email_address ?: 'correo@temporal.com',
                    "phone" => [
                        "indicative" => "57",
                        "number" => $cleanPhone,
                        "extension" => null
                    ]
                ]
            ]
        ];

        if ($checkDigit !== null && $checkDigit !== '') {
            $payload['check_digit'] = $checkDigit;
        }

        if ($taxDetails && !empty($taxDetails->business_name)) {
            $payload['commercial_name'] = $taxDetails->business_name;
        }

        return $payload;
    }

    public static function getCustomerIdentification(Customer $customer): string
    {
        $taxDetails = $customer->taxDetails;
        if ($taxDetails && !empty($taxDetails->tax_identification_number)) {
            $id = $taxDetails->tax_identification_number;
            if (strpos($id, '-') !== false) {
                return substr($id, 0, strpos($id, '-'));
            }
            return $id;
        }
        return $customer->identity_document ?: '';
    }

    public static function buildInvoicePayload(\App\Models\Invoice\Invoice $invoice): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);
        
        $items = [];
        foreach ($invoice->items as $item) {
            $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId();
            $itemTax = [];
            if ($taxId) {
                $itemTax[] = ['id' => $taxId];
            }
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode() ?: 'ISP01',
                'description' => $item->description ?: 'Servicio de Internet',
                'quantity' => (int) ($item->quantity ?: 1),
                'price' => (float) ($item->unit_price ?: 0),
                'discount' => 0.0,
                'tax' => $itemTax
            ];
        }

        if (empty($items)) {
            $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId();
            $itemTax = [];
            if ($taxId) {
                $itemTax[] = ['id' => $taxId];
            }
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode() ?: 'ISP01',
                'description' => 'Servicios de Internet - Factura ' . $invoice->increment_id,
                'quantity' => 1,
                'price' => (float) $invoice->total,
                'discount' => 0.0,
                'tax' => $itemTax
            ];
        }

        $paymentId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getPaymentId() ?: 12;

        $payload = [
            'document' => [
                'id' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getDocumentId() ?: 24445
            ],
            'date' => $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : now()->format('Y-m-d'),
            'customer' => [
                'identification' => $identification,
                'branch_office' => 0
            ],
            'observations' => $invoice->notes ?: 'Factura generada por ISP Go',
            'items' => $items,
            'payments' => [
                [
                    'id' => $paymentId,
                    'value' => (float) $invoice->total,
                    'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : now()->addDays(30)->format('Y-m-d')
                ]
            ],
            'stamp' => [
                'send' => true
            ]
        ];

        $sellerId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getSellerId();
        if ($sellerId) {
            $payload['seller'] = $sellerId;
        }

        return $payload;
    }

    public static function buildVoucherPayload(\App\Models\Invoice\Invoice $invoice, float $amount): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);
        
        $info = $invoice->additional_information ?? [];
        $prefix = $info['siigo_prefix'] ?? 'FV';
        $consecutive = (int) ($info['siigo_consecutive'] ?? 0);
        $date = $info['siigo_date'] ?? ($invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : now()->format('Y-m-d'));

        $payload = [
            'document' => [
                'id' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getVoucherDocumentId() ?: 24446
            ],
            'date' => now()->format('Y-m-d'),
            'type' => 'Detailed',
            'customer' => [
                'identification' => $identification,
                'branch_office' => 0
            ],
            'items' => [
                [
                    'account' => [
                        'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getVoucherAccountDebit() ?: '11100501',
                        'movement' => 'Debit'
                    ],
                    'description' => 'Pago Recibido Factura ' . $invoice->increment_id,
                    'value' => (float) $amount
                ],
                [
                    'account' => [
                        'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getVoucherAccountCredit() ?: '13050501',
                        'movement' => 'Credit'
                    ],
                    'due' => [
                        'prefix' => $prefix,
                        'consecutive' => $consecutive,
                        'quote' => 1,
                        'date' => $date
                    ],
                    'description' => 'Abono Factura ' . $invoice->increment_id,
                    'value' => (float) $amount
                ]
            ],
            'observations' => 'Recibo de caja generado por ISP Go para factura ' . $invoice->increment_id
        ];

        return $payload;
    }

    public static function buildCreditNotePayload(\App\Models\Invoice\Invoice $invoice): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);

        $info = $invoice->additional_information ?? [];
        $invoiceUuid = $info['siigo_invoice_id'] ?? '';

        $items = [];
        foreach ($invoice->items as $item) {
            $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId();
            $itemTax = [];
            if ($taxId) {
                $itemTax[] = ['id' => $taxId];
            }
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode() ?: 'ISP01',
                'description' => 'Anulación: ' . ($item->description ?: 'Servicio de Internet'),
                'quantity' => (int) ($item->quantity ?: 1),
                'price' => (float) ($item->unit_price ?: 0),
                'discount' => 0.0,
                'tax' => $itemTax
            ];
        }

        if (empty($items)) {
            $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId();
            $itemTax = [];
            if ($taxId) {
                $itemTax[] = ['id' => $taxId];
            }
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode() ?: 'ISP01',
                'description' => 'Anulación Factura ' . $invoice->increment_id,
                'quantity' => 1,
                'price' => (float) $invoice->total,
                'discount' => 0.0,
                'tax' => $itemTax
            ];
        }

        $paymentId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getPaymentId() ?: 12;

        $payload = [
            'document' => [
                'id' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getCreditNoteDocumentId() ?: 24447
            ],
            'date' => now()->format('Y-m-d'),
            'invoice' => $invoiceUuid,
            'reason' => 1,
            'observations' => 'Nota crédito generada automáticamente por anulación de factura ' . $invoice->increment_id,
            'items' => $items,
            'payments' => [
                [
                    'id' => $paymentId,
                    'value' => (float) $invoice->total
                ]
            ],
            'stamp' => [
                'send' => true
            ]
        ];

        return $payload;
    }

    public static function mapStateAndCity(?string $stateName, ?string $cityName): array
    {
        $stateClean = strtolower(trim($stateName ?? ''));
        $cityClean = strtolower(trim($cityName ?? ''));

        // Default: Bogotá
        $stateCode = '11';
        $cityCode = '11001';

        // State Mapping
        if (strpos($stateClean, 'cauca') !== false) {
            if (strpos($stateClean, 'valle') !== false) {
                $stateCode = '76'; // Valle del Cauca
            } else {
                $stateCode = '19'; // Cauca
            }
        } elseif (strpos($stateClean, 'valle') !== false) {
            $stateCode = '76';
        } elseif (strpos($stateClean, 'bogota') !== false || strpos($stateClean, 'cundinamarca') !== false) {
            $stateCode = '11';
        }

        // City Mapping
        if ($stateCode === '19') {
            // Cauca Cities
            if (strpos($cityClean, 'santander') !== false || strpos($cityClean, 'quilichao') !== false) {
                $cityCode = '19698';
            } elseif (strpos($cityClean, 'guachene') !== false) {
                $cityCode = '19318';
            } elseif (strpos($cityClean, 'popayan') !== false) {
                $cityCode = '19001';
            } elseif (strpos($cityClean, 'caloto') !== false) {
                $cityCode = '19142';
            } elseif (strpos($cityClean, 'villa rica') !== false || strpos($cityClean, 'villarica') !== false) {
                $cityCode = '19845';
            } elseif (strpos($cityClean, 'puerto tejada') !== false || strpos($cityClean, 'tejada') !== false) {
                $cityCode = '19573';
            } elseif (strpos($cityClean, 'miranda') !== false) {
                $cityCode = '19455';
            } elseif (strpos($cityClean, 'corinto') !== false) {
                $cityCode = '19212';
            } elseif (strpos($cityClean, 'padilla') !== false) {
                $cityCode = '19517';
            }
        } elseif ($stateCode === '76') {
            // Valle del Cauca Cities
            if (strpos($cityClean, 'cali') !== false) {
                $cityCode = '76001';
            } elseif (strpos($cityClean, 'jamundi') !== false) {
                $cityCode = '76364';
            } elseif (strpos($cityClean, 'palmira') !== false) {
                $cityCode = '76520';
            }
        }

        return [
            'state_code' => $stateCode,
            'city_code' => $cityCode,
        ];
    }
}
