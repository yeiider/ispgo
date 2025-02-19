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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('column_id')->comment('Columna a la que pertenece la tarea');
            $table->string('title')->comment('Título de la tarea');
            $table->text('description')->nullable()->comment('Descripción detallada de la tarea');
            $table->unsignedBigInteger('created_by')->comment('Usuario que creó la tarea');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Último usuario que actualizó la tarea');

            // Relación opcional con clientes y servicios
            $table->unsignedBigInteger('customer_id')->nullable()->comment('Cliente asociado a la tarea');
            $table->unsignedBigInteger('service_id')->nullable()->comment('Servicio asociado a la tarea');

            // Fecha de vencimiento, prioridad, etc.
            $table->dateTime('due_date')->nullable()->comment('Fecha de vencimiento');
            $table->string('priority')->default('normal')->comment('Prioridad de la tarea');

            $table->timestamps();

            // Foreign keys
            $table->foreign('column_id')->references('id')->on('columns')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('service_id')->references('id')->on('services');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tasks');
        Schema::enableForeignKeyConstraints();

    }
};
