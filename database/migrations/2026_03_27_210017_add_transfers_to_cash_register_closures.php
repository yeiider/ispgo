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
        Schema::table('cash_register_closures', function (Blueprint $table) {
            $table->decimal('total_transfers_out', 15, 2)->default(0)->after('total_expenses');
            $table->decimal('total_transfers_in', 15, 2)->default(0)->after('total_transfers_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_register_closures', function (Blueprint $table) {
            $table->dropColumn(['total_transfers_out', 'total_transfers_in']);
        });
    }
};
