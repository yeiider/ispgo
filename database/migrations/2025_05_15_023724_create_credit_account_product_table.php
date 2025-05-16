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
        Schema::create('credit_account_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_account_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            $table->index(['credit_account_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_account_product');
    }
};
