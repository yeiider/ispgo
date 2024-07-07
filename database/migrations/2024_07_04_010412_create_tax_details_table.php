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
            $table->string('business_name'); // Razón social
            $table->boolean('enable_billing')->default(false); // Habilitar facturación
            $table->boolean('send_notifications')->default(false); // Enviar notificaciones
            $table->boolean('send_invoice')->default(false); // Enviar notificaciones
            $table->unsignedBigInteger('created_by')->nullable(); // ID del usuario que creó el plan
            $table->unsignedBigInteger('updated_by')->nullable(); // ID del usuario que actualizó el plan por última vez
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tax_details');
    }
}
