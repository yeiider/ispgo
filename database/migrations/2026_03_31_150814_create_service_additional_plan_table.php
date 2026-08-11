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
        Schema::create('service_additional_plan', function (Blueprint $row) {
            $row->id();
            $row->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $row->foreignId('additional_plan_id')->constrained('additional_plans')->onDelete('cascade');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_additional_plan');
    }
};
