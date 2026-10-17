<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hilos de cada cable (solo se guardan los que no están libres), empalmes en las
 * muflas para seguir un hilo de punta a punta, y terceros a quienes se venden hilos.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiber_third_parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document', 50)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fiber_strands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiber_cable_id')->constrained('fiber_cables')->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            // used, reserved, leased (vendido a un tercero), damaged
            $table->string('status', 20)->default('used');
            // pon, link (enlace a otro nodo), third_party, other
            $table->string('usage', 20)->nullable();
            $table->foreignId('olt_id')->nullable()->constrained('olts')->nullOnDelete();
            $table->string('pon_port', 50)->nullable();
            $table->foreignId('third_party_id')->nullable()->constrained('fiber_third_parties')->nullOnDelete();
            $table->string('label')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['fiber_cable_id', 'number']);
        });

        Schema::create('fiber_splices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiber_node_id')->constrained('fiber_nodes')->cascadeOnDelete();
            $table->foreignId('in_cable_id')->constrained('fiber_cables')->cascadeOnDelete();
            $table->unsignedSmallInteger('in_strand');
            $table->foreignId('out_cable_id')->constrained('fiber_cables')->cascadeOnDelete();
            $table->unsignedSmallInteger('out_strand');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['in_cable_id', 'in_strand', 'out_cable_id']);
            $table->unique(['out_cable_id', 'out_strand']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiber_splices');
        Schema::dropIfExists('fiber_strands');
        Schema::dropIfExists('fiber_third_parties');
    }
};
