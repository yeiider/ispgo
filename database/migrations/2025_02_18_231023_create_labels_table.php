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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre de la etiqueta, por ejemplo: Bug, Feature, etc.');
            $table->string('color')->comment('Clase o valor de color (bg-red-500 text-white, etc.)');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('labels');
        Schema::enableForeignKeyConstraints();

    }
};
