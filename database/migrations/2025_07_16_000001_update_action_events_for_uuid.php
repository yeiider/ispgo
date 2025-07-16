<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_events', function (Blueprint $table) {
            $table->uuid('actionable_id')->nullable()->change();
            $table->uuid('target_id')->nullable()->change();
            $table->uuid('model_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('action_events', function (Blueprint $table) {
            $table->unsignedBigInteger('actionable_id')->nullable()->change();
            $table->unsignedBigInteger('target_id')->nullable()->change();
            $table->unsignedBigInteger('model_id')->nullable()->change();
        });
    }
};
