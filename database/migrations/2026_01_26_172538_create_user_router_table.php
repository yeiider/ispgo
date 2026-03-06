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
        Schema::create('user_router', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('router_id')->constrained('routers')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['user_id', 'router_id']);
            
            // Índices para mejorar performance de queries
            $table->index('user_id');
            $table->index('router_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_router');
    }
};
