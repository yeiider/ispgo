<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_wifi_access', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone_number', 15);
            $table->string('email');
            $table->foreignId('router_id')->constrained('routers')->onDelete('cascade');
            $table->string('otp_code', 6);
            $table->enum('otp_method', ['email', 'whatsapp'])->default('email');
            $table->timestamp('otp_expires_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('access_expires_at')->nullable(); // Acceso válido por 24h
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 17)->nullable();
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['email', 'verified_at']);
            $table->index(['phone_number', 'verified_at']);
            $table->index(['access_expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_wifi_access');
    }
};
