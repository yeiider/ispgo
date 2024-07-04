<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetPlansTable extends Migration
{
    public function up()
    {
        Schema::create('internet_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('download_speed');
            $table->integer('upload_speed');
            $table->decimal('monthly_price', 8, 2);
            $table->integer('data_limit')->nullable(); // In GB
            $table->boolean('unlimited_data')->default(false);
            $table->string('contract_period')->nullable();
            $table->text('promotions')->nullable();
            $table->text('extras_included')->nullable();
            $table->text('geographic_availability')->nullable();
            $table->date('promotion_start_date')->nullable();
            $table->date('promotion_end_date')->nullable();
            $table->string('plan_image')->nullable();
            $table->decimal('customer_rating', 2, 1)->nullable(); // 1 decimal place
            $table->text('customer_reviews')->nullable();
            $table->text('service_compatibility')->nullable();
            $table->string('network_priority')->nullable();
            $table->text('technical_support')->nullable();
            $table->text('additional_benefits')->nullable();
            $table->string('connection_type'); // Fiber Optic, ADSL, Satellite
            $table->string('status'); // Active, Inactive, Pending
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('internet_plans');
    }
}
