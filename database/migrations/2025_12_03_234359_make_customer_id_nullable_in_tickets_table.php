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
        Schema::table('tickets', function (Blueprint $table) {
            // Make customer_id nullable
            $table->unsignedBigInteger('customer_id')->nullable()->change();

            // Add labels column if not exists
            if (!Schema::hasColumn('tickets', 'labels')) {
                $table->json('labels')->nullable()->after('contact_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Revert customer_id to NOT NULL
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();

            // Drop labels column if exists
            if (Schema::hasColumn('tickets', 'labels')) {
                $table->dropColumn('labels');
            }
        });
    }
};
