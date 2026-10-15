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
        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('receiver_id');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('reference_number')->nullable()->after('account_number');
            $table->date('deposit_date')->nullable()->after('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'reference_number', 'deposit_date']);
        });
    }
};
