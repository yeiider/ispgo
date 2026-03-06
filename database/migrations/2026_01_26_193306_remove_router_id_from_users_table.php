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
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key first if exists
            try {
                $table->dropForeign(['router_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }
            
            // Drop the column
            $table->dropColumn('router_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the column
            $table->unsignedBigInteger('router_id')->nullable()->after('updated_by');
            
            // Restore the foreign key
            $table->foreign('router_id')->references('id')->on('routers')->onDelete('set null');
        });
    }
};
