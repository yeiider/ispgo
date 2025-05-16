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
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_account_id')->constrained()->onDelete('cascade');
            $table->dateTime('paid_at');
            $table->decimal('amount', 15, 2);
            $table->string('method');
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('paid_at');
            $table->index('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_payments');
    }
};
