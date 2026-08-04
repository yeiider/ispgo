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
        Schema::table('product_warehouse_stock', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->default(0.00)->change();
        });

        Schema::table('equipment_assignments', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->default(1.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_warehouse_stock', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->change();
        });

        Schema::table('equipment_assignments', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->change();
        });
    }
};
