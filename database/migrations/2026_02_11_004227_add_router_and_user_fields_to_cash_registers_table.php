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
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->foreignId('router_id')->nullable()->after('name')->constrained('routers')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->after('router_id')->constrained('users')->onDelete('set null');
            $table->enum('status', ['open', 'closed'])->default('open')->after('current_balance');
            $table->timestamp('opened_at')->nullable()->after('status');
            $table->timestamp('closed_at')->nullable()->after('opened_at');
            $table->text('notes')->nullable()->after('closed_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->dropForeign(['router_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['name', 'router_id', 'user_id', 'status', 'opened_at', 'closed_at', 'notes', 'created_by', 'updated_by']);
        });
    }
};
