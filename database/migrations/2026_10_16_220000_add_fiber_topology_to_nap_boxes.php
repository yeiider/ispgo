<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Topología de la red de fibra: de qué OLT y puerto PON sale cada caja NAP,
 * de qué caja cuelga (cascada), qué splitter tiene y por dónde va la fibra.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('nap_boxes', function (Blueprint $table) {
            $table->foreignId('olt_id')->nullable()->after('router_id')->constrained('olts')->nullOnDelete();
            $table->string('pon_port', 50)->nullable()->after('olt_id');
            $table->string('splitter_ratio', 10)->nullable()->after('pon_port');
            // Puntos [lat, lng] del tramo que llega a esta caja desde su padre (o la OLT)
            $table->json('route')->nullable()->after('fiber_color');
            $table->unsignedInteger('cable_length_m')->nullable()->after('route');
        });

        Schema::table('olts', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('host');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('address')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('nap_boxes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('olt_id');
            $table->dropColumn(['pon_port', 'splitter_ratio', 'route', 'cable_length_m']);
        });

        Schema::table('olts', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'address']);
        });
    }
};
