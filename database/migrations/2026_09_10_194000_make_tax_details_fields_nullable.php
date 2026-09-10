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
            $table->string('tax_identification_type', 50)->nullable()->change();
            $table->string('business_name', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_details', function (Blueprint $table) {
            $table->string('tax_identification_type', 5)->nullable(false)->change();
            $table->string('business_name', 255)->nullable(false)->change();
        });
    }
};
