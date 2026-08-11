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
        Schema::table('tax_details', function (Blueprint $table) {
            $table->string('siigo_customer_id')->nullable()->after('send_invoice');
            $table->timestamp('siigo_synced_at')->nullable()->after('siigo_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_details', function (Blueprint $table) {
            $table->dropColumn(['siigo_customer_id', 'siigo_synced_at']);
        });
    }
};
