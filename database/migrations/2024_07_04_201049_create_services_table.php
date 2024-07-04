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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('router_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('internet_plan_id');
            $table->string('service_ip');
            $table->string('username_router');
            $table->string('password_router');
            $table->enum('service_status', ['active', 'inactive', 'suspended', 'pending'])->default('active');
            $table->date('activation_date')->nullable();
            $table->date('deactivation_date')->nullable();
            $table->integer('bandwidth')->nullable(); // Assuming bandwidth in Mbps
            $table->string('mac_address')->nullable();
            $table->date('installation_date')->nullable();
            $table->text('service_notes')->nullable();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->string('support_contact')->nullable();
            $table->string('service_location')->nullable();
            $table->string('service_type')->nullable(); // e.g., Fiber Optic, ADSL, Satellite
            $table->boolean('static_ip')->default(false);
            $table->integer('data_limit')->nullable(); // In GB
            $table->date('last_maintenance')->nullable();
            $table->string('billing_cycle')->nullable(); // e.g., Monthly, Bimonthly
            $table->decimal('monthly_fee', 8, 2)->nullable();
            $table->decimal('overage_fee', 8, 2)->nullable();
            $table->enum('service_priority', ['normal', 'high', 'critical'])->default('normal');
            $table->unsignedBigInteger('assigned_technician')->nullable();
            $table->text('service_contract')->nullable();
            $table->timestamps();

            $table->foreign('router_id')->references('id')->on('routers')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('internet_plan_id')->references('id')->on('internet_plans')->onDelete('restrict');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
