<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nap_ports', function (Blueprint $table) {
            $table->id();
            // Relación con la caja NAP
            $table->foreignId('nap_box_id')
                ->constrained('nap_boxes')
                ->onDelete('cascade');

            // Información básica del puerto
            $table->integer('port_number'); // Número del puerto (1, 2, 3, etc.)
            $table->string('port_name')->nullable(); // Nombre personalizado del puerto

            // Estado del puerto
            $table->enum('status', [
                'available',    // Disponible
                'occupied',     // Ocupado
                'damaged',      // Dañado
                'maintenance',  // En mantenimiento
                'reserved',     // Reservado
                'testing'       // En pruebas
            ])->default('available');

            // Configuración técnica
            $table->enum('connection_type', [
                'fiber',     // Fibra óptica
                'coaxial',   // Cable coaxial
                'ethernet',  // Ethernet
                'mixed'      // Conexión mixta
            ])->default('fiber');

            // Información del servicio (si está ocupado)
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->onDelete('set null');

            $table->datetime('last_signal_check')->nullable(); // Última verificación de señal
            $table->date('last_maintenance')->nullable(); // Último mantenimiento
            $table->date('warranty_until')->nullable(); // Garantía hasta

            $table->text('technician_notes')->nullable();

            //$table->integer('max_speed')->nullable(); // Velocidad máxima en Mbps
            //$table->integer('current_speed')->nullable(); // Velocidad actual asignada

            // Información técnica
            $table->decimal('signal_strength', 5, 2)->nullable(); // Fuerza de señal (0-100)
            $table->json('port_config')->nullable(); // Configuración técnica adicional

            // Índices para optimizar consultas
            $table->index(['nap_box_id', 'port_number']);
            $table->index(['status']);
            $table->index(['service_id']);
            $table->index(['connection_type']);
            $table->index(['signal_strength']);
            $table->index(['last_signal_check']);

            // Restricción única: un puerto por número en cada caja NAP
            $table->unique(['nap_box_id', 'port_number'], 'unique_port_per_nap');
            $table->timestamps();
        });;
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nap_ports');
    }
};
