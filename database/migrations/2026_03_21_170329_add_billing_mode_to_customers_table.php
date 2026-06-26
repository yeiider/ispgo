<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * billing_mode:
     *   null / 'total'       → Comportamiento por defecto: una sola factura con el total de todos los servicios.
     *   'per_service'        → Facturación por servicio: cada servicio genera su propia factura con service_id.
     *                          La suspensión evalúa servicio a servicio en lugar del cliente completo.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('billing_mode', 20)->nullable()->after('router_id')
                ->comment('null|total = una sola factura por cliente; per_service = factura individual por servicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('billing_mode');
        });
    }
};
