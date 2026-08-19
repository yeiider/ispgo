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

        $dbPersonType = $taxDetails ? strtolower((string)$taxDetails->taxpayer_type) : "person";
        $personType = "Person";
        if (in_array($dbPersonType, ['personas_juridicas', 'company', 'juridica', 'empresa', 'regimen_simple', 'regimen_ordinario', 'grandes_contribuyentes'])) {
            $personType = "Company";
        }

        // Map document types to Siigo codes: CC -> 13, NIT -> 31, CE -> 22, PAS -> 41, TI -> 12, RC -> 11
        $docType = strtoupper($taxDetails ? ($taxDetails->tax_identification_type ?: $customer->document_type) : $customer->document_type);
        $idType = '13'; // Default to Cédula
        if ($docType === 'NIT' || $docType === '31') {
            $idType = '31';
        } elseif ($docType === 'CE' || $docType === '22' || str_contains($docType, 'EXTRANJER')) {
            $idType = '22';
        } elseif ($docType === 'PAS' || $docType === 'PP' || $docType === '41' || str_contains($docType, 'PASAPORTE')) {
            $idType = '41';
        } elseif ($docType === 'TI' || $docType === '12' || str_contains($docType, 'TARJETA DE IDENTIDAD')) {
            $idType = '12';
        } elseif ($docType === 'RC' || $docType === '11') {
            $idType = '11';
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

        // Mapping Fiscal Regime and VAT Responsibility according to Siigo API rules
        $vatResponsible = false;
        $fiscalRegimeCode = $personType === 'Company' ? 'O-99' : 'R-99-PN'; // Default

        if ($taxDetails && !empty($taxDetails->fiscal_regime)) {
            $regimeRaw = strtolower((string)$taxDetails->fiscal_regime);
            if (in_array($regimeRaw, ['general', 'responsible', 'responsable', 'responsable_iva', 'comun'])) {
                $vatResponsible = true;
                $fiscalRegimeCode = $personType === 'Company' ? 'O-99' : 'R-99-PN';
            } elseif (in_array($regimeRaw, ['gran_contribuyente', 'gran contribuyente', 'o-13'])) {
                $vatResponsible = true;
                $fiscalRegimeCode = "O-13";
            } elseif (in_array($regimeRaw, ['autorretenedor', 'o-15'])) {
                $vatResponsible = true;
                $fiscalRegimeCode = "O-15";
            } elseif (in_array($regimeRaw, ['agente_retencion', 'o-23'])) {
                $vatResponsible = true;
                $fiscalRegimeCode = "O-23";
            } elseif (in_array($regimeRaw, ['regimen_simple', 'simple', 'o-47'])) {
                $vatResponsible = false;
                $fiscalRegimeCode = "O-47";
            } elseif (in_array($regimeRaw, ['simplified', 'nonresponsible', 'no_responsable', 'no_responsable_iva', 'r-99-pn', 'simplificado'])) {
                $vatResponsible = false;
                $fiscalRegimeCode = $personType === 'Company' ? 'O-99' : 'R-99-PN';
            } elseif (in_array(strtoupper($taxDetails->fiscal_regime), ['O-13', 'O-15', 'O-23', 'O-47', 'R-99-PN', 'O-99'])) {
                $fiscalRegimeCode = strtoupper($taxDetails->fiscal_regime);
                $vatResponsible = in_array($fiscalRegimeCode, ['O-13', 'O-15', 'O-23']);
            }
        }

        $scopeId = (int) ($customer->router_id ?? 0);
        $addressText = $addressObj ? $addressObj->address : 'Direccion';
        $country = $addressObj ? ($addressObj->country ?? 'CO') : 'CO';

        $mappedCity = self::mapStateAndCity(
            $addressObj ? $addressObj->state_province : null,
            $addressObj ? $addressObj->city : null,
            $scopeId
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
            "vat_responsible" => $vatResponsible,
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
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);
        
        $items = [];
        $subtotalTotal = (float) $invoice->subtotal;
        $invoiceTotal = (float) $invoice->total;
        $invoiceItems = $invoice->items;
        $itemCount = count($invoiceItems);

        if ($itemCount > 0) {
            $currentSum = 0;
            foreach ($invoiceItems as $index => $item) {
                $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId($scopeId);
                $itemTax = [];
                if ($taxId) {
                    $itemTax[] = ['id' => $taxId];
                }

                $qty = max(1, (int) ($item->quantity ?: 1));
                $itemSubtotal = (float) ($item->subtotal ?: ($item->unit_price * $qty));

                if ($subtotalTotal > 0) {
                    $itemTotalAmount = round(($itemSubtotal / $subtotalTotal) * $invoiceTotal, 2);
                } else {
                    $itemTotalAmount = round($invoiceTotal / $itemCount, 2);
                }

                if ($index === $itemCount - 1) {
                    $itemTotalAmount = round($invoiceTotal - $currentSum, 2);
                } else {
                    $currentSum += $itemTotalAmount;
                }

                $pricePerUnit = round($itemTotalAmount / $qty, 2);

                $items[] = [
                    'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                    'description' => $item->description ?: 'Servicio de Internet',
                    'quantity' => $qty,
                    'price' => $pricePerUnit,
                    'discount' => 0.0,
                    'tax' => $itemTax
                ];
            }
        }

        if (empty($items)) {
            $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId($scopeId);
            $itemTax = [];
            if ($taxId) {
                $itemTax[] = ['id' => $taxId];
            }
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                'description' => 'Servicios de Internet - Factura ' . $invoice->increment_id,
                'quantity' => 1,
                'price' => $invoiceTotal,
                'discount' => 0.0,
                'tax' => $itemTax
            ];
        }

        $paymentId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getPaymentId($scopeId) ?: 12;

        $payload = [
            'document' => [
                'id' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getDocumentId($scopeId) ?: 24445
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
                    'value' => $invoiceTotal,
                    'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : now()->addDays(30)->format('Y-m-d')
                ]
            ],
            'stamp' => [
                'send' => false
            ]
        ];

        $costCenter = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getCostCenter($scopeId);
        if ($costCenter) {
            $payload['cost_center'] = $costCenter;
        }

        $sellerId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getSellerId($scopeId);
        if ($sellerId) {
            $payload['seller'] = $sellerId;
        }

        return $payload;
    }

    public static function buildVoucherPayload(\App\Models\Invoice\Invoice $invoice, float $amount): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);
        
        $info = $invoice->additional_information ?? [];
        $consecutive = (int) ($info['siigo_consecutive'] ?? 0);
        $prefix = $info['siigo_prefix'] ?? 'FV';

        // Dynamically extract prefix from full invoice name (e.g. "FV-993-90000000192" -> "FV-993")
        if (!empty($info['siigo_name']) && $consecutive > 0) {
            $suffix = '-' . $consecutive;
            if (str_ends_with($info['siigo_name'], $suffix)) {
                $prefix = substr($info['siigo_name'], 0, -strlen($suffix));
            }
        }
        $date = $info['siigo_date'] ?? ($invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : now()->format('Y-m-d'));

        $voucherDocumentId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getVoucherDocumentId($scopeId) ?: 10597;
        $voucherPaymentId = !empty($info['siigo_payment_id'])
            ? (int) $info['siigo_payment_id']
            : \Ispgo\Siigo\Settings\ConfigProviderSiigo::getVoucherPaymentIdForMethod($invoice->payment_method ?? 'cash', $scopeId);
        $costCenter = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getCostCenter($scopeId);

        $payload = [
            'document' => [
                'id' => $voucherDocumentId
            ],
            'date' => now()->format('Y-m-d'),
            'type' => 'DebtPayment',
            'customer' => [
                'identification' => $identification,
                'branch_office' => 0
            ],
            'items' => [
                [
                    'due' => [
                        'prefix' => $prefix,
                        'consecutive' => $consecutive,
                        'quote' => 1,
                        'date' => $date
                    ],
                    'value' => (float) $amount
                ]
            ],
            'payment' => [
                'id' => $voucherPaymentId,
                'value' => (float) $amount
            ],
            'observations' => 'Recibo de caja generado por ISP Go para factura ' . $invoice->increment_id
        ];

        if ($costCenter) {
            $payload['cost_center'] = $costCenter;
        }

        return $payload;
    }

    public static function buildCreditNotePayload(\App\Models\Invoice\Invoice $invoice): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);

        $info = $invoice->additional_information ?? [];
        $invoiceUuid = $info['siigo_invoice_id'] ?? '';

        $items = [];
        $subtotalTotal = (float) $invoice->subtotal;
        $invoiceTotal = (float) $invoice->total;
        $invoiceItems = $invoice->items;
        $itemCount = count($invoiceItems);

        if ($itemCount > 0) {
            $currentSum = 0;
            foreach ($invoiceItems as $index => $item) {
                $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId($scopeId);
                $itemTax = [];
                if ($taxId) {
                    $itemTax[] = ['id' => $taxId];
                }

                $qty = max(1, (int) ($item->quantity ?: 1));
                $itemSubtotal = (float) ($item->subtotal ?: ($item->unit_price * $qty));

                if ($subtotalTotal > 0) {
                    $itemTotalAmount = round(($itemSubtotal / $subtotalTotal) * $invoiceTotal, 2);
                } else {
                    $itemTotalAmount = round($invoiceTotal / $itemCount, 2);
                }

                if ($index === $itemCount - 1) {
                    $itemTotalAmount = round($invoiceTotal - $currentSum, 2);
                } else {
                    $currentSum += $itemTotalAmount;
                }

                $pricePerUnit = round($itemTotalAmount / $qty, 2);

                $items[] = [
                    'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                    'description' => 'Anulación: ' . ($item->description ?: 'Servicio de Internet'),
                    'quantity' => $qty,
                    'price' => $pricePerUnit,
                    'discount' => 0.0,
                    'tax' => $itemTax
                ];
            }
        }

        if (empty($items)) {
            $taxId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId($scopeId);
            $itemTax = [];
            if ($taxId) {
                $itemTax[] = ['id' => $taxId];
            }
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                'description' => 'Anulación Factura ' . $invoice->increment_id,
                'quantity' => 1,
                'price' => $invoiceTotal,
                'discount' => 0.0,
                'tax' => $itemTax
            ];
        }

        $paymentId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getPaymentId($scopeId) ?: 12;

        $payload = [
            'document' => [
                'id' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getCreditNoteDocumentId($scopeId) ?: 24447
            ],
            'date' => now()->format('Y-m-d'),
            'invoice' => $invoiceUuid,
            'reason' => 1,
            'observations' => 'Nota crédito generada automáticamente por anulación de factura ' . $invoice->increment_id,
            'items' => $items,
            'payments' => [
                [
                    'id' => $paymentId,
                    'value' => (float) $invoice->total,
                    'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : now()->format('Y-m-d')
                ]
            ],
            'stamp' => [
                'send' => false
            ]
        ];

        $costCenter = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getCostCenter($scopeId);
        if ($costCenter) {
            $payload['cost_center'] = $costCenter;
        }

        $sellerId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getSellerId($scopeId);
        if ($sellerId) {
            $payload['seller'] = $sellerId;
        }

        return $payload;
    }

    public static function mapStateAndCity(?string $stateName, ?string $cityName, int $scopeId = 0): array
    {
        $defaultCityCode = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getDefaultCityCode($scopeId);
        return ColombiaDivipolaCatalog::resolve($stateName, $cityName, $defaultCityCode);
    }
}
