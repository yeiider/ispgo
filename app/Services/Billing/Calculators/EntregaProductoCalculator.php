<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

class EntregaProductoCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo por entrega de productos al cliente.
     * El cargo se basa en el precio unitario y la cantidad de productos.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Verificar que exista un producto y cantidad -------------- */
        $productId = $novedad->product_id;
        $quantity = $novedad->quantity ?? 1;

        if (!$productId || $quantity <= 0) {
            return 0; // No hay producto o cantidad válida
        }

        /* 2️⃣  Obtener el precio unitario ------------------------------ */
        $unitPrice = $novedad->unit_price;

        // Si no hay precio unitario en la novedad, intentar obtenerlo del producto
        if (!$unitPrice && $novedad->product) {
            $unitPrice = $novedad->product->price ?? 0;
        }

        if ($unitPrice <= 0) {
            return 0; // No hay precio válido
        }

        /* 3️⃣  Obtener la regla para configuraciones adicionales -------- */
        $rule = $novedad->rule ?? [];

        /* 4️⃣  Aplicar descuento si existe ----------------------------- */
        $discountPercentage = $rule['discount_percentage'] ?? 0;
        if ($discountPercentage > 0) {
            $discountPercentage = min(100, $discountPercentage); // Limitar a 100%
            $unitPrice = $unitPrice * (1 - $discountPercentage / 100);
        }

        /* 5️⃣  Aplicar cargo de envío si existe ------------------------ */
        $shippingFee = $rule['shipping_fee'] ?? 0;

        /* 6️⃣  Calcular el total --------------------------------------- */
        $totalAmount = ($unitPrice * $quantity) + $shippingFee;

        /* 7️⃣  Devolver el resultado como cargo (valor positivo) ------- */
        return abs(round($totalAmount, 2));
    }
}
