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
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'category')) {
                $table->dropColumn('category');
            }
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete();
            $table->foreignId('daily_box_id')->nullable()->constrained('daily_boxes')->nullOnDelete();
            // In laravel 10+, change() might need DBAL for some types, but string is usually safe.
            // If it fails, we can add it differently. For now let's try.
            $table->string('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->dropForeign(['expense_category_id']);
            $table->dropColumn('expense_category_id');
            $table->dropForeign(['daily_box_id']);
            $table->dropColumn('daily_box_id');
            $table->string('description')->nullable(false)->change();
        });
    }
};
