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
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status', 20)->default('draft')->after('end_date');
            $table->string('contract_pdf_path', 255)->nullable()->after('signed_at');
            $table->string('cedula_path', 255)->nullable()->after('contract_pdf_path');
            $table->string('utility_bill_path', 255)->nullable()->after('cedula_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['status', 'contract_pdf_path', 'cedula_path', 'utility_bill_path']);
        });
    }
};
