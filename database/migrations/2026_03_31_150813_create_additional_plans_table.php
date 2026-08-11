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
        Schema::create('additional_plans', function (Blueprint $row) {
            $row->id();
            $row->string('name');
            $row->decimal('monthly_price', 15, 2);
            $row->enum('status', ['active', 'inactive'])->default('active');
            $row->text('description')->nullable();
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_plans');
    }
};
