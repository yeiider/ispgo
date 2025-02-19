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
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id')->comment('Tablero al que pertenece la columna');
            $table->string('title')->comment('Título de la columna');
            $table->integer('position')->default(0)->comment('Orden de prioridad');
            $table->timestamps();

            // Relación con boards
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('columns');
        Schema::enableForeignKeyConstraints();

    }
};
