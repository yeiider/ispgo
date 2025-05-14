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
        Schema::create('invoice_adjustments', function (Blueprint $t) {
            $t->id();

            // factura afectada
            $t->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $t->foreignId('invoice_item_id')->nullable()->constrained();

            // relación polimórfica con la “fuente” del ajuste
            $t->morphs('source');          // source_type, source_id

            // clasificación contable del ajuste
            $t->enum('kind', [
                'charge',      // suma al total (cargo)
                'discount',    // resta (descuento / nota crédito)
                'tax',         // impuesto adicional
                'void',        // anula parcial / totalmente
            ]);

            $t->decimal('amount', 12, 2);   // signo coherente con kind
            $t->string('label')->nullable(); // “Prorrateo marzo”, “Mes gratis”, etc.
            $t->json('metadata')->nullable();

            // trazabilidad
            $t->foreignId('created_by')->nullable()->constrained('users');
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_adjustments');
    }
};
