<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNotesTable extends Migration
{
    public function up()
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id'); // Usuario que creó la nota de crédito
            $table->decimal('amount', 10, 2); // Monto de la nota de crédito
            $table->date('issue_date');
            $table->text('reason')->nullable(); // Razón de la nota de crédito
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('credit_notes');
    }
}
