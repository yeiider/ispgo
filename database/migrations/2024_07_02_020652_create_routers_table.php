<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->string('name');
            $table->string('ip');
            $table->string('failover')->nullable();
            $table->string('rb_user');
            $table->string('rb_password');
            $table->integer('api_port')->default(8728);
            $table->integer('www_port')->default(80);
            $table->string('lan_interface')->default('ether2');
            $table->text('ip_ranges')->nullable();
            $table->text('comments')->nullable();
            $table->string('coordinates')->nullable();
            $table->string('version')->nullable();
            $table->string('service_cut_type')->default('Corte de servicio por address list moroso');
            $table->boolean('add_client_mikrotik')->default(false);
            $table->boolean('system_level_ips')->default(false);
            $table->boolean('traffic_history')->default(false);
            $table->boolean('simple_queue_control')->default(false);
            $table->boolean('pcq_addresslist_control')->default(false);
            $table->boolean('hotspot_control')->default(false);
            $table->boolean('pppoe_control')->default(false);
            $table->boolean('ip_bindings')->default(false);
            $table->boolean('ip_mac_binding')->default(false);
            $table->boolean('dhcp_leases')->default(false);
            $table->boolean('general_failure')->default(false);
            $table->boolean('ipv6')->default(false);
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routers');
    }
}
