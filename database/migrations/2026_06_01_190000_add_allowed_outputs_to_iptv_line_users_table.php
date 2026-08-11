<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('iptv_line_users', function (Blueprint $table) {
            $table->text('allowed_outputs')->nullable()->after('bouquets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iptv_line_users', function (Blueprint $table) {
            $table->dropColumn('allowed_outputs');
        });
    }
};
