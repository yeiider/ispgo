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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id'); // Usuario que registró el pago
            $table->decimal('amount', 10, 2); // Monto del abono/pago
            $table->date('payment_date');
            $table->string('payment_method')->nullable(); // cash, transfer, card, online
            $table->string('reference_number')->nullable(); // Número de referencia del pago
            $table->text('notes')->nullable();
            $table->string('payment_support')->nullable(); // Ruta al comprobante de pago
            $table->json('additional_information')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
