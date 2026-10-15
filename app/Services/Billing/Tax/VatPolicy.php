<?php

namespace App\Services\Billing\Tax;

use App\Models\Customers\TaxDetail;

/**
 * Single source of truth for VAT (IVA) rules.
 *
 * Every component that needs to know whether a customer is VAT responsible,
 * which rate applies, or how a gross amount splits into base + tax MUST use
 * this class, so local invoices and Siigo documents never disagree.
 */
final class VatPolicy
{
    /** Colombian standard VAT rate (19%). */
    public const RATE = 0.19;

    /** Label used by the auto-generated IVA adjustment. */
    public const ADJUSTMENT_LABEL = 'IVA 19%';

    /**
     * Fiscal regime values (lower-cased) that are VAT responsible.
     * Includes the catalog codes (`general`) and legacy/imported variants.
     */
    private const RESPONSIBLE_REGIMES = [
        'general', 'responsible', 'responsable', 'responsable_iva', 'comun',
        'gran_contribuyente', 'gran contribuyente', 'o-13',
        'autorretenedor', 'o-15',
        'agente_retencion', 'o-23',
    ];

    public static function isVatResponsible(?string $fiscalRegime): bool
    {
        if ($fiscalRegime === null || trim($fiscalRegime) === '') {
            return false;
        }

        return in_array(strtolower(trim($fiscalRegime)), self::RESPONSIBLE_REGIMES, true);
    }

    public static function isTaxDetailVatResponsible(?TaxDetail $taxDetail): bool
    {
        return $taxDetail !== null && self::isVatResponsible($taxDetail->fiscal_regime);
    }

    /**
     * VAT for a taxable base, rounded to cents (same rounding used everywhere).
     */
    public static function taxFor(float $base, float $rate = self::RATE): float
    {
        return round($base * $rate, 2);
    }

    /**
     * Base contained in a gross (VAT-included) amount, rounded to cents.
     */
    public static function baseFromGross(float $gross, float $rate = self::RATE): float
    {
        return round($gross / (1 + $rate), 2);
    }
}
