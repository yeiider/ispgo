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
        Schema::create('service_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['percentage', 'fixed', 'free_month']);   // % | $ fijo | mes gratis
            $table->decimal('value', 10, 2)->nullable();                   // nulo si es free_month
            $table->unsignedSmallInteger('cycles');                        // nº de ciclos a aplicar
            $table->unsignedSmallInteger('cycles_used')->default(0);       // tracking
            $table->timestamp('starts_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_rules');
    }
};
