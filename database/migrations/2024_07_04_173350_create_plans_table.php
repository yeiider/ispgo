<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('download_speed')->default(0);
            $table->integer('upload_speed')->default(0);
            $table->decimal('monthly_price', 8, 2);
            $table->integer('data_limit')->nullable(); // In GB
            $table->boolean('unlimited_data')->default(false);
            $table->string('contract_period')->nullable();
            $table->text('promotions')->nullable();
            $table->text('extras_included')->nullable();
            $table->text('geographic_availability')->nullable();
            $table->datetime('promotion_start_date')->nullable();
            $table->datetime('promotion_end_date')->nullable();
            $table->string('plan_image')->nullable();
            $table->decimal('customer_rating', 2, 1)->nullable(); // 1 decimal place
            $table->text('customer_reviews')->nullable();
            $table->text('service_compatibility')->nullable();
            $table->string('network_priority')->nullable();
            $table->text('technical_support')->nullable();
            $table->text('additional_benefits')->nullable();
            $table->string('connection_type'); // Fiber Optic, ADSL, Satellite
            $table->enum('plan_type', ['internet', 'television', 'telephonic'])->default('internet'); // Fiber Optic, ADSL, Satellite
            $table->enum('modality_type', ['prepaid', 'postpaid'])->default('postpaid'); // Fiber Optic, ADSL, Satellite
            $table->enum('status',['active','inactive']); // Active, Inactive

            $table->unsignedBigInteger('created_by')->nullable(); // ID del usuario que creó el plan
            $table->unsignedBigInteger('updated_by')->nullable(); // ID del usuario que actualizó el plan por última vez
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
