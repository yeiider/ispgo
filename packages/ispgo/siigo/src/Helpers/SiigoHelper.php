<?php

namespace Ispgo\Siigo\Helpers;

use App\Models\Customers\Customer;
use App\Services\Billing\Tax\VatPolicy;
use Ispgo\Siigo\Support\InvoiceLineBuilder;

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
                ? mb_strtoupper($taxDetails->business_name, 'UTF-8')
                : mb_strtoupper(trim($customer->first_name . ' ' . $customer->last_name), 'UTF-8');
        } else {
            $name[] = mb_strtoupper($customer->first_name ?: 'N/A', 'UTF-8');
            $name[] = mb_strtoupper($customer->last_name ?: 'N/A', 'UTF-8');
        }

        // VAT responsibility: single rule shared with local invoicing (VatPolicy).
        $vatResponsible = VatPolicy::isTaxDetailVatResponsible($taxDetails);

        // Fiscal responsibility code accepted by Siigo/DIAN.
        $fiscalRegimeCode = 'R-99-PN'; // Default: "No aplica - Otros"

        if ($taxDetails && !empty($taxDetails->fiscal_regime)) {
            $regimeRaw = strtolower(trim((string) $taxDetails->fiscal_regime));
            if (in_array($regimeRaw, ['gran_contribuyente', 'gran contribuyente', 'o-13'])) {
                $fiscalRegimeCode = 'O-13';
            } elseif (in_array($regimeRaw, ['autorretenedor', 'o-15'])) {
                $fiscalRegimeCode = 'O-15';
            } elseif (in_array($regimeRaw, ['agente_retencion', 'o-23'])) {
                $fiscalRegimeCode = 'O-23';
            } elseif (in_array($regimeRaw, ['regimen_simple', 'simple', 'o-47'])) {
                $fiscalRegimeCode = 'O-47';
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
                    "first_name" => mb_strtoupper($customer->first_name ?: 'N/A', 'UTF-8'),
                    "last_name" => mb_strtoupper($customer->last_name ?: 'N/A', 'UTF-8'),
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
            $payload['commercial_name'] = mb_strtoupper($taxDetails->business_name, 'UTF-8');
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

    public static function isCustomerVatResponsible(?Customer $customer): bool
    {
        return $customer !== null && VatPolicy::isTaxDetailVatResponsible($customer->taxDetails);
    }

    /**
     * Description shown in Siigo for an invoice item.
     */
    private static function describeItem($item): string
    {
        if ($item->service && $item->service->plan) {
            $plan = $item->service->plan;
            return !empty(trim($plan->description ?? '')) ? $plan->description : $plan->name;
        }

        return !empty($item->description) ? $item->description : 'Servicio de Internet';
    }

    public static function getInvoiceTaxId(\App\Models\Invoice\Invoice $invoice, int $scopeId): ?int
    {
        $hasInvoiceTax = ((float) ($invoice->tax ?? 0) > 0) || ((float) ($invoice->tax_total ?? 0) > 0);

        if ($hasInvoiceTax) {
            return \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId($scopeId);
        }

        return null;
    }

    /**
     * @param string|null $discountType Siigo document discount type ('Value'|'Percentage').
     *                                  null = unknown: discounts are folded into net prices.
     */
    public static function buildInvoicePayload(\App\Models\Invoice\Invoice $invoice, bool $sendStamp = false, ?string $discountType = null): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);
        
        $items = [];
        $subtotalTotal = (float) $invoice->subtotal;
        $invoiceTotal = (float) $invoice->total;

        $taxId = self::getInvoiceTaxId($invoice, $scopeId);
        $itemTax = [];
        if ($taxId) {
            $itemTax[] = ['id' => $taxId];
        }

        $targetBase = ($taxId && $subtotalTotal > 0) ? $subtotalTotal : $invoiceTotal;
        $productCode = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01';

        $lines = InvoiceLineBuilder::build(
            $invoice->items,
            $targetBase,
            fn ($item) => self::describeItem($item),
            $discountType
        );

        foreach ($lines as $line) {
            $items[] = [
                'code' => $productCode,
                'description' => $line['description'],
                'quantity' => $line['quantity'],
                'price' => $line['price'],
                'discount' => $line['discount'],
                'taxes' => $itemTax
            ];
        }

        if (empty($items)) {
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                'description' => 'Servicios de Internet - Factura ' . $invoice->increment_id,
                'quantity' => 1,
                'price' => $targetBase,
                'discount' => 0.0,
                'taxes' => $itemTax
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
                'send' => $sendStamp
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

        $taxId = self::getInvoiceTaxId($invoice, $scopeId);
        $itemTax = [];
        if ($taxId) {
            $itemTax[] = ['id' => $taxId];
        }

        $targetBase = ($taxId && $subtotalTotal > 0) ? $subtotalTotal : $invoiceTotal;
        $productCode = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01';

        // Credit-note document config is unknown here: fold discounts into net prices.
        $lines = InvoiceLineBuilder::build(
            $invoice->items,
            $targetBase,
            fn ($item) => 'Anulación: ' . ($item->description ?: 'Servicio de Internet')
        );

        foreach ($lines as $line) {
            $items[] = [
                'code' => $productCode,
                'description' => $line['description'],
                'quantity' => $line['quantity'],
                'price' => $line['price'],
                'discount' => $line['discount'],
                'taxes' => $itemTax
            ];
        }

        if (empty($items)) {
            $items[] = [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                'description' => 'Anulación Factura ' . $invoice->increment_id,
                'quantity' => 1,
                'price' => $targetBase,
                'discount' => 0.0,
                'taxes' => $itemTax
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

    /**
     * @param float $discountBase Taxable base reduced by the discount.
     * @param float $taxAmount    VAT reduced with it (0 = untaxed discount).
     */
    public static function buildDiscountCreditNotePayload(\App\Models\Invoice\Invoice $invoice, float $discountBase, string $reasonNote = '', float $taxAmount = 0.0): array
    {
        $customer = $invoice->customer;
        $identification = self::getCustomerIdentification($customer);
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);

        $info = $invoice->additional_information ?? [];
        $invoiceUuid = $info['siigo_invoice_id'] ?? '';

        $discountBase = round($discountBase, 2);
        $taxId = $taxAmount > 0 ? \Ispgo\Siigo\Settings\ConfigProviderSiigo::getTaxId($scopeId) : null;
        $itemTax = [];
        if ($taxId) {
            $itemTax[] = ['id' => $taxId];
        }

        // Must equal what Siigo computes: base + VAT(base) with the same rounding.
        $paymentValue = $taxId
            ? round($discountBase + VatPolicy::taxFor($discountBase), 2)
            : $discountBase;

        $items = [
            [
                'code' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getProductCode($scopeId) ?: 'ISP01',
                'description' => 'Descuento / Rebaja: ' . ($reasonNote ?: ('Factura ' . $invoice->increment_id)),
                'quantity' => 1,
                'price' => $discountBase,
                'discount' => 0.0,
                'taxes' => $itemTax
            ]
        ];

        $paymentId = \Ispgo\Siigo\Settings\ConfigProviderSiigo::getPaymentId($scopeId) ?: 12;

        $obsText = 'Nota crédito por descuento parcial en factura ' . $invoice->increment_id;
        if (!empty($reasonNote)) {
            $obsText .= ': ' . $reasonNote;
        }

        $payload = [
            'document' => [
                'id' => \Ispgo\Siigo\Settings\ConfigProviderSiigo::getCreditNoteDocumentId($scopeId) ?: 24447
            ],
            'date' => now()->format('Y-m-d'),
            'invoice' => $invoiceUuid,
            'reason' => 3, // Motivo 3 en Siigo/DIAN: Rebaja o descuento parcial/total
            'observations' => $obsText,
            'items' => $items,
            'payments' => [
                [
                    'id' => $paymentId,
                    'value' => $paymentValue,
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
