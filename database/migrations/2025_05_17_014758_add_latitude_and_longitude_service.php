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
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('unu_latitude', 10, 8)->nullable(); // Cambia "some_column" por la columna existente después de la cual deseas agregar latitud
            $table->decimal('unu_longitude', 11, 8)->nullable(); // Agrega longitud justo después de latitud
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['unu_latitude', 'unu_longitude']);
        });
    }
};
