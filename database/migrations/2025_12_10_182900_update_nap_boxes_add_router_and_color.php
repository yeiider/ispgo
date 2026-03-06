<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nap_boxes', function (Blueprint $table) {
            if (!Schema::hasColumn('nap_boxes', 'router_id')) {
                $table->foreignId('router_id')->nullable()->after('parent_nap_id')
                    ->constrained('routers')->nullOnDelete();
            }
            if (!Schema::hasColumn('nap_boxes', 'fiber_color')) {
                $table->string('fiber_color')->nullable()->after('router_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nap_boxes', function (Blueprint $table) {
            if (Schema::hasColumn('nap_boxes', 'router_id')) {
                $table->dropConstrainedForeignId('router_id');
            }
            if (Schema::hasColumn('nap_boxes', 'fiber_color')) {
                $table->dropColumn('fiber_color');
            }
        });
    }
};
