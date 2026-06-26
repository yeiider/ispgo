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
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('daily_box_id')->nullable()->after('invoice_id')->index();
            // Relationship with cash_registers
            // $table->foreign('daily_box_id')->references('id')->on('cash_registers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('daily_box_id');
        });
    }
};
