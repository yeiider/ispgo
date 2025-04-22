<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Truncar la tabla para evitar conflictos con datos existentes
        DB::table('action_events')->truncate();

        // Cambiar columnas a unsignedBigInteger
        Schema::table('action_events', function (Blueprint $table) {
            $table->unsignedBigInteger('actionable_id')->change();
            $table->unsignedBigInteger('target_id')->change();
            $table->unsignedBigInteger('model_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cambiar columnas de regreso a string
        Schema::table('action_events', function (Blueprint $table) {
            $table->string('actionable_id', 36)->change();
            $table->string('target_id', 36)->change();
            $table->string('model_id', 36)->change();
        });
    }
};
