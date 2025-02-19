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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del tablero');
            $table->text('description')->nullable()->comment('Descripción corta del tablero');
            $table->unsignedBigInteger('created_by')->comment('Usuario que creó el tablero');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Último usuario que actualizó el tablero');
            $table->timestamps();

            // Relación con usuarios
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
