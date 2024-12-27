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
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('is_singned');
            $table->boolean('is_signed')
                ->default(false)
                ->after('end_date');
            $table->dateTime('signed_at')
                ->nullable()
                ->after('is_signed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('is_signed');
            $table->dropColumn('signed_at');
            $table->string('is_singned', 10)->default('no');
        });
    }
};
