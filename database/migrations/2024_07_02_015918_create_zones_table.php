<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('invoice_notice_type');
            $table->string('create_invoice_day');
            $table->time('create_invoice_time');
            $table->string('payment_day');
            $table->string('payment_reminder');
            $table->string('send_push_notifications');
            $table->string('cutoff_day');
            $table->string('suspend_service');
            $table->integer('taxes');
            $table->boolean('automatic_cut');
            $table->boolean('automatic_invoice');
            $table->boolean('screen_notice');
            $table->boolean('payment_reminder_notice');
            $table->boolean('receive_cut_email');
            $table->boolean('receive_invoice_email');
            $table->boolean('receive_notice_email');
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
        Schema::dropIfExists('zones');
    }
}
