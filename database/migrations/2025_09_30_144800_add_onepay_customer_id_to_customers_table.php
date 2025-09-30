<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'onepay_customer_id')) {
                $table->string('onepay_customer_id')->nullable()->unique()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'onepay_customer_id')) {
                $table->dropUnique(['onepay_customer_id']);
                $table->dropColumn('onepay_customer_id');
            }
        });
    }
};
