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
        Schema::create('account_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('entry_type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->morphs('creditable');
            $table->timestamps();

            $table->index('entry_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_entries');
    }
};
