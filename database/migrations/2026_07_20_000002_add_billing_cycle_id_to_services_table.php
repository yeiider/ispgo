<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('billing_cycle_id')->nullable()->after('billing_cycle');
            $table->foreign('billing_cycle_id')->references('id')->on('billing_cycles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['billing_cycle_id']);
            $table->dropColumn('billing_cycle_id');
        });
    }
};
