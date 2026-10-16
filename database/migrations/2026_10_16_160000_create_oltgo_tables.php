<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Configuración que OLTGO (servicio Go de solo lectura) usa para llegar a las OLT.
 * ISPGO es dueño de estas tablas; OLTGO solo las lee.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('network_tunnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->string('name');
            $table->string('type')->default('wireguard');
            // Lado MikroTik
            $table->string('endpoint_host');
            $table->unsignedInteger('endpoint_port')->default(13231);
            $table->string('remote_public_key');
            $table->string('remote_network')->nullable()->comment('Red real de las OLT, ej. 136.1.1.0/24');
            // Lado OLTGO
            $table->string('local_address')->comment('IP de OLTGO dentro del túnel, ej. 192.168.60.250/32');
            $table->text('local_private_key')->comment('Cifrada con OLTGO_SECRET_KEY');
            $table->string('local_public_key');
            $table->string('translated_network')->nullable()->comment('Red única por zona que ve OLTGO, ej. 10.200.1.0/24');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('olts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->foreignId('network_tunnel_id')->nullable()->constrained('network_tunnels')->nullOnDelete();
            $table->string('name');
            $table->string('vendor')->default('zte');
            $table->string('model');
            $table->string('host')->comment('IP real de gestión de la OLT');
            $table->unsignedInteger('snmp_port')->default(161);
            $table->string('snmp_version')->default('2c');
            $table->text('snmp_community')->nullable()->comment('Cifrada con OLTGO_SECRET_KEY');
            $table->string('snmp_v3_user')->nullable();
            $table->string('snmp_v3_level')->nullable();
            $table->string('snmp_v3_auth_proto')->nullable();
            $table->text('snmp_v3_auth_pass')->nullable();
            $table->string('snmp_v3_priv_proto')->nullable();
            $table->text('snmp_v3_priv_pass')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('olts');
        Schema::dropIfExists('network_tunnels');
    }
};
