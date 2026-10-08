<?php

namespace Ispgo\Siigo\Support;

/**
 * Builds Siigo invoice/credit-note lines from local invoice items.
 *
 * Local discounts are stored as items with a NEGATIVE subtotal, which Siigo
 * rejects. This builder folds those negative lines into the positive ones,
 * using the item `discount` field when it can be represented EXACTLY under
 * the document's discount type, and falling back to net pricing otherwise.
 *
 * Invariant: Σ(line net) === $targetBase (to the cent). This guarantees the
 * Siigo-computed total matches the payment value (no invalid_total_payments).
 */
final class InvoiceLineBuilder
{
    public const DISCOUNT_VALUE = 'Value';
    public const DISCOUNT_PERCENTAGE = 'Percentage';

    /**
     * @param iterable      $items        Invoice items (need ->subtotal, ->quantity, ->unit_price).
     * @param float         $targetBase   Net base Siigo must compute after discounts.
     * @param callable      $describe     fn($item): string
     * @param string|null   $discountType Document discount type ('Value'|'Percentage'); null = unknown.
     * @return array<int, array{description: string, quantity: int, price: float, discount: float}>
     */
    public static function build(iterable $items, float $targetBase, callable $describe, ?string $discountType = null): array
    {
        $positives = [];
        $negativeSum = 0.0;

        foreach ($items as $item) {
            $qty = max(1, (int) ($item->quantity ?: 1));
            $subtotal = (float) ($item->subtotal ?: ((float) $item->unit_price * $qty));

            if ($subtotal > 0) {
                $positives[] = ['item' => $item, 'qty' => $qty, 'subtotal' => $subtotal];
            } elseif ($subtotal < 0) {
                $negativeSum += abs($subtotal);
            }
        }

        $gross = array_sum(array_column($positives, 'subtotal'));
        $netLocal = $gross - $negativeSum;

        if (empty($positives) || $netLocal <= 0 || $targetBase <= 0) {
            return [];
        }

        // Scale lines so that net === targetBase (keeps the previous proportional behaviour).
        $factor = $targetBase / $netLocal;
        $scaledGross = self::distribute(round($gross * $factor, 2), array_column($positives, 'subtotal'));
        $totalDiscount = round(array_sum($scaledGross) - $targetBase, 2);
        $discounts = $totalDiscount > 0
            ? self::distribute($totalDiscount, $scaledGross)
            : array_fill(0, count($scaledGross), 0.0);

        $lines = [];
        foreach ($positives as $i => $p) {
            $lineGross = $scaledGross[$i];
            $lines[] = [
                'description' => (string) $describe($p['item']),
                'qty'         => $p['qty'],
                'gross'       => $lineGross,
                'discount'    => $discounts[$i],
                'net'         => round($lineGross - $discounts[$i], 2),
            ];
        }

        $useDiscountField = $totalDiscount > 0 && self::canRepresentExactly($lines, $discountType);

        return array_map(function (array $line) use ($useDiscountField, $discountType) {
            if ($useDiscountField) {
                // Discounted lines use quantity 1 to avoid unit/line ambiguity in Siigo.
                return [
                    'description' => $line['description'],
                    'quantity'    => 1,
                    'price'       => $line['gross'],
                    'discount'    => $discountType === self::DISCOUNT_PERCENTAGE
                        ? self::percentage($line)
                        : $line['discount'],
                ];
            }

            return self::netLine($line['description'], $line['qty'], $line['net']);
        }, $lines);
    }

    private static function canRepresentExactly(array $lines, ?string $discountType): bool
    {
        if ($discountType === self::DISCOUNT_VALUE) {
            return true;
        }

        if ($discountType !== self::DISCOUNT_PERCENTAGE) {
            return false;
        }

        foreach ($lines as $line) {
            if ($line['discount'] <= 0) {
                continue;
            }
            $pct = self::percentage($line);
            $siigoDiscount = round($line['gross'] * $pct / 100, 2);
            $siigoNet = round($line['gross'] - $siigoDiscount, 2);
            if (abs($siigoNet - $line['net']) >= 0.005 || abs(round($line['gross'] * (1 - $pct / 100), 2) - $line['net']) >= 0.005) {
                return false;
            }
        }

        return true;
    }

    private static function percentage(array $line): float
    {
        return $line['gross'] > 0 ? round($line['discount'] / $line['gross'] * 100, 2) : 0.0;
    }

    /**
     * Net-priced line. Keeps the quantity only when price × qty is exact.
     */
    private static function netLine(string $description, int $qty, float $net): array
    {
        $unit = round($net / $qty, 2);
        if ($qty > 1 && abs(round($unit * $qty, 2) - $net) >= 0.005) {
            $qty = 1;
            $unit = $net;
        }

        return ['description' => $description, 'quantity' => $qty, 'price' => $unit, 'discount' => 0.0];
    }

    /**
     * Splits $amount proportionally to $weights; the last slot absorbs rounding.
     *
     * @param float[] $weights
     * @return float[]
     */
    private static function distribute(float $amount, array $weights): array
    {
        $weights = array_values($weights);
        $sum = array_sum($weights);
        $count = count($weights);
        $result = [];
        $acc = 0.0;

        foreach ($weights as $i => $w) {
            if ($i === $count - 1) {
                $result[] = round($amount - $acc, 2);
                break;
            }
            $part = $sum > 0 ? round($amount * $w / $sum, 2) : round($amount / $count, 2);
            $result[] = $part;
            $acc += $part;
        }

        return $result;
    }
}
