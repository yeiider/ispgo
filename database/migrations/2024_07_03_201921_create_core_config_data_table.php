<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreConfigDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_config_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scope_id')->default(0);
            $table->string('path');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['scope_id', 'path']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_config_data');
    }
}
