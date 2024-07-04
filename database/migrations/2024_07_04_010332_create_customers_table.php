<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->date('date_of_birth')->nullable();
            $table->string('phone_number',12)->nullable();
            $table->string('email_address')->unique();
            $table->string('document_type',5);
            $table->string('identity_document',12);
            $table->enum('customer_status',["active","inactive","suspended"])->default("active");
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
