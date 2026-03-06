<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_register_closures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->date('closure_date');

            // Saldos
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->default(0);
            $table->decimal('expected_balance', 15, 2)->default(0);
            $table->decimal('difference', 15, 2)->default(0);

            // Totales por método de pago
            $table->decimal('total_cash', 15, 2)->default(0);
            $table->decimal('total_transfer', 15, 2)->default(0);
            $table->decimal('total_card', 15, 2)->default(0);
            $table->decimal('total_online', 15, 2)->default(0);
            $table->decimal('total_other', 15, 2)->default(0);

            // Estadísticas de facturas
            $table->integer('total_invoices')->default(0);
            $table->integer('paid_invoices')->default(0);
            $table->decimal('total_collected', 15, 2)->default(0);
            $table->decimal('total_discounts', 15, 2)->default(0);
            $table->decimal('total_adjustments', 15, 2)->default(0);

            // Detalles del cierre
            $table->json('payment_details')->nullable(); // Desglose detallado por método
            $table->json('invoice_summary')->nullable(); // Resumen de facturas
            $table->json('metadata')->nullable(); // Información adicional

            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['cash_register_id', 'closure_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_register_closures');
    }
};
