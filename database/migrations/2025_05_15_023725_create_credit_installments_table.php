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
        Schema::create('credit_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_account_id')->constrained()->onDelete('cascade');
            $table->date('due_date');
            $table->decimal('amount_due', 15, 2);
            $table->decimal('interest_portion', 15, 2);
            $table->decimal('principal_portion', 15, 2);
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();

            $table->index('due_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_installments');
    }
};
