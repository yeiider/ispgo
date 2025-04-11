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
        Schema::table('daily_boxes', function (Blueprint $table) {
            $table->dropColumn('transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_boxes', function (Blueprint $table) {
            $table->addColumn('text', 'transactions')->nullable();
        });
    }
};
