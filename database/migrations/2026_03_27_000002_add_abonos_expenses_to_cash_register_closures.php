<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_register_closures', function (Blueprint $table) {
            // Total de abonos parciales (InvoicePayment) registrados en este cierre
            $table->decimal('total_abonos', 15, 2)->default(0)->after('total_other');
            // Total de gastos (Expense) registrados en este cierre
            $table->decimal('total_expenses', 15, 2)->default(0)->after('total_abonos');
        });
    }

    public function down(): void
    {
        Schema::table('cash_register_closures', function (Blueprint $table) {
            $table->dropColumn(['total_abonos', 'total_expenses']);
        });
    }
};
