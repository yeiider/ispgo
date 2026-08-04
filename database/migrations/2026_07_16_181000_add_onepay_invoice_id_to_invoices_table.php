<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'onepay_invoice_id')) {
                $table->string('onepay_invoice_id')->nullable()->unique()->after('onepay_charge_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'onepay_invoice_id')) {
                $table->dropUnique(['onepay_invoice_id']);
                $table->dropColumn('onepay_invoice_id');
            }
        });
    }
};
