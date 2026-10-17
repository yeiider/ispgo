<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Centros de la red: datacenter (de donde se recibe la fibra/internet) y nodos de
 * zona (POP) ligados al router de la zona, con proveedor y capacidad recibida.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('fiber_nodes', function (Blueprint $table) {
            $table->foreignId('router_id')->nullable()->after('olt_id')->constrained('routers')->nullOnDelete();
            $table->string('provider')->nullable()->after('router_id');
            $table->unsignedInteger('capacity_mbps')->nullable()->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('fiber_nodes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('router_id');
            $table->dropColumn(['provider', 'capacity_mbps']);
        });
    }
};
