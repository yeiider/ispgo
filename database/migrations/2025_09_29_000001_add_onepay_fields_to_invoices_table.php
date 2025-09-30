<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('onepay_charge_id')->nullable()->unique()->after('payment_link');
            $table->string('onepay_payment_link')->nullable()->after('onepay_charge_id');
            $table->string('onepay_status')->nullable()->default('pending')->after('onepay_payment_link');
            $table->json('onepay_metadata')->nullable()->after('onepay_status');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['onepay_charge_id', 'onepay_payment_link', 'onepay_status', 'onepay_metadata']);
        });
    }
};
