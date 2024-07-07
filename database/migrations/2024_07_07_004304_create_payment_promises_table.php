<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPromisesTable extends Migration
{
    public function up()
    {
        Schema::create('payment_promises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id'); // Usuario que creó la promesa de pago
            $table->decimal('amount', 10, 2); // Monto de la promesa de pago
            $table->date('promise_date'); // Fecha en que se realizará el pago
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_promises');
    }
}
