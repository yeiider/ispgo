<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Planta externa general (no depende de zonas/routers): nodos (central, mufla,
 * reserva, poste...) unidos por cables troncales con N hilos. Las cajas NAP raíz
 * se empalman en una mufla, en un hilo de un cable.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiber_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->nullable()->unique();
            // central (sale de una OLT), mufla, reserva, poste, odf
            $table->string('type', 20)->default('mufla');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            $table->foreignId('olt_id')->nullable()->constrained('olts')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fiber_cables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->nullable()->unique();
            // troncal, distribucion
            $table->string('kind', 20)->default('troncal');
            $table->unsignedSmallInteger('fiber_count')->default(24);
            $table->foreignId('from_node_id')->constrained('fiber_nodes')->cascadeOnDelete();
            $table->foreignId('to_node_id')->constrained('fiber_nodes')->cascadeOnDelete();
            // Puntos intermedios [lat, lng] entre los dos nodos
            $table->json('route')->nullable();
            $table->unsignedInteger('length_m')->nullable();
            $table->string('status', 20)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('nap_boxes', function (Blueprint $table) {
            $table->foreignId('fiber_node_id')->nullable()->after('splitter_ratio')->constrained('fiber_nodes')->nullOnDelete();
            $table->foreignId('fiber_cable_id')->nullable()->after('fiber_node_id')->constrained('fiber_cables')->nullOnDelete();
            $table->unsignedSmallInteger('fiber_strand')->nullable()->after('fiber_cable_id');
        });
    }

    public function down(): void
    {
        Schema::table('nap_boxes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fiber_cable_id');
            $table->dropConstrainedForeignId('fiber_node_id');
            $table->dropColumn('fiber_strand');
        });
        Schema::dropIfExists('fiber_cables');
        Schema::dropIfExists('fiber_nodes');
    }
};
