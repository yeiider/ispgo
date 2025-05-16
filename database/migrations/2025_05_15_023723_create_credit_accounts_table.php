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
        Schema::create('credit_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->decimal('principal', 15, 2);
            $table->decimal('interest_rate', 5, 2)->comment('Annual interest rate percentage');
            $table->integer('grace_period_days')->default(0);
            $table->enum('status', ['active', 'in_grace', 'overdue', 'closed'])->default('active');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_accounts');
    }
};
