<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id'); // Usuario que procesó el pago
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('outstanding_balance', 10, 2); // Saldo pendiente
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('status', ['paid', 'unpaid', 'overdue', 'canceled'])->default('unpaid');
            $table->string('payment_method')->nullable(); // Método de pago puede ser nulo
            $table->text('notes')->nullable();
            $table->string('payment_support')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // ID del usuario que creó el plan
            $table->unsignedBigInteger('updated_by')->nullable(); // ID del usuario que actualizó el plan por última vez
            $table->timestamps();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('restrict');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
