<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('tax_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('tax_identification_type', 5);
            $table->string('tax_identification_number')->unique();
            $table->string('taxpayer_type');
            $table->string('fiscal_regime');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tax_details');
    }
}