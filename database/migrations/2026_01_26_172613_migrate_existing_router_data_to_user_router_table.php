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
        // Migrar datos existentes de users.router_id a user_router
        DB::statement('
            INSERT INTO user_router (user_id, router_id, created_at, updated_at)
            SELECT id, router_id, NOW(), NOW()
            FROM users
            WHERE router_id IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpiar la tabla user_router
        DB::table('user_router')->truncate();
    }
};
