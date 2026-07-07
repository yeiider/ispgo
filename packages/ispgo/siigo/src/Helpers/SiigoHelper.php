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
}
