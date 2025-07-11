<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nap_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('status', ['active', 'inactive', 'maintenance', 'damaged'])->default('active');
            $table->integer('capacity');
            $table->enum('technology_type', ['fiber', 'coaxial', 'ftth', 'mixed']);
            $table->date('installation_date');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('distribution_order')->default(0);
            $table->unsignedBigInteger('parent_nap_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_nap_id')->references('id')->on('nap_boxes')->onDelete('set null');
            $table->index(['latitude', 'longitude']);
            $table->index('status');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nap_boxes');
    }
};
