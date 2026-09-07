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
        if (Schema::hasTable('activity_logs')) {
            return;
        }
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('action'); // e.g. "created", "updated", "deleted", "authorized", "transferred", "assigned"
            $table->string('module'); // e.g. "tickets", "users", "inventory", "customers", "services", "equipment"
            $table->string('description'); // Human readable description e.g. "admin creó el ticket #102"
            $table->nullableMorphs('subject'); // subject_type y subject_id
            $table->json('properties')->nullable(); // Para guardar deltas, cambios, o datos contextuales adicionales
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
