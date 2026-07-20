<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_issue_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default issue types
        $defaultTypes = [
            'Problema de Conexión',
            'Lentitud de Internet',
            'Sin Servicio',
            'Problema de Router',
            'Facturación',
            'Cambio de Plan',
            'Instalación',
            'Consulta General',
            'Otro'
        ];

        $now = now();
        foreach ($defaultTypes as $type) {
            DB::table('ticket_issue_types')->insert([
                'name' => $type,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_issue_types');
    }
};
