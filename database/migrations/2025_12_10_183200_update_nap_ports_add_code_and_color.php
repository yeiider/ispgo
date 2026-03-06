<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nap_ports', function (Blueprint $table) {
            if (!Schema::hasColumn('nap_ports', 'code')) {
                $table->string('code')->nullable()->after('service_id');
                $table->index('code');
            }
            if (!Schema::hasColumn('nap_ports', 'color')) {
                $table->string('color')->nullable()->after('code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nap_ports', function (Blueprint $table) {
            if (Schema::hasColumn('nap_ports', 'color')) {
                $table->dropColumn('color');
            }
            if (Schema::hasColumn('nap_ports', 'code')) {
                $table->dropIndex(['code']);
                $table->dropColumn('code');
            }
        });
    }
};
