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
        Schema::create('frontend_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('guard_name')->default('frontend');
            $table->timestamps();
        });

        Schema::create('role_frontend_permission', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('frontend_permission_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('frontend_permission_id')
                ->references('id')
                ->on('frontend_permissions')
                ->onDelete('cascade');

            $table->primary(['role_id', 'frontend_permission_id'], 'role_front_perm_primary');
        });

        Schema::create('user_frontend_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('frontend_permission_id');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('frontend_permission_id', 'fk_user_front_perm')
                ->references('id')->on('frontend_permissions')
                ->onDelete('cascade');

            $table->primary(['user_id', 'frontend_permission_id'], 'user_front_perm_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_frontend_permission');
        Schema::dropIfExists('role_frontend_permission');
        Schema::dropIfExists('frontend_permissions');
    }
};
