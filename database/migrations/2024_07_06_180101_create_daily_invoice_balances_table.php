<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('daily_invoice_balances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_invoices');
            $table->integer('paid_invoices');
            $table->decimal('total_subtotal', 10, 2);
            $table->decimal('total_tax', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('total_discount', 10, 2);
            $table->decimal('total_outstanding_balance', 10, 2);
            $table->decimal('total_revenue', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_invoice_balances');
    }
};
