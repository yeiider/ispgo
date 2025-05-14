<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_novedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained();      // Servicio al que afecta
            $table->foreignId('customer_id')->constrained();     // Redundante pero útil para reportes
            $table->enum('type', [
                'saldo_favor',
                'cargo_adicional',
                'prorrateo_inicial',
                'prorrateo_cancelacion',
                'cambio_plan',
                'descuento_promocional',
                'cargo_reconexion',
                'mora',
                'nota_credito',
                'compensacion',
                'exceso_consumo',
                'impuesto',
                'product_delivery'
            ]);
            $table->json('product_lines')->nullable();   // [{product_id, qty, unit_price, total}, …]
            $table->unsignedInteger('quantity')->nullable();             // Unidades (≥1)
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('amount', 12, 2);                    // Positivo = cargo; negativo = descuento
            $table->text('description')->nullable();             // Visible en la factura
            $table->json('rule')->nullable();                    // Datos para calcular automáticamente (días, %, etc.)
            $table->date('effective_period')->nullable();        // Periodo facturable al que se aplicará
            $table->boolean('applied')->default(false);          // Se marca true cuando se enlaza a una invoice
            $table->foreignId('invoice_id')->nullable();         // (null hasta que se genera la factura)
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_novedades');
    }
};
