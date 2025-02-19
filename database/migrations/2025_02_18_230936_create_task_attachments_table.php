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
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->comment('Tarea a la que pertenece el adjunto');
            $table->string('file_path')->comment('Ruta/URL del archivo');
            $table->string('file_name')->nullable()->comment('Nombre original del archivo');
            $table->unsignedBigInteger('uploaded_by')->comment('Usuario que sube el archivo');
            $table->timestamps();

            // Foreign keys
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('task_attachments');
        Schema::enableForeignKeyConstraints();

    }
};
