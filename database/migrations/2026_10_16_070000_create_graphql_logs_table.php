<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graphql_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('operation_type', 20); // query | mutation
            $table->string('operation_name')->nullable(); // nombre de la operación enviada por el cliente
            $table->string('field', 120)->index(); // campo raíz llamado, ej. updateInvoice
            $table->string('action', 20)->index(); // view | create | update | delete | other
            $table->string('entity', 120)->nullable()->index(); // ej. Invoice
            $table->string('target_id', 64)->nullable()->index();
            $table->string('summary', 500); // descripción corta y legible
            $table->json('details')->nullable(); // argumentos y cambios resumidos
            $table->string('status', 10)->default('success'); // success | error
            $table->string('error', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graphql_logs');
    }
};
