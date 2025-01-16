<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payment_link')->nullable()->after('status'); // Field for the payment link
            $table->dateTime('expiration_date')->nullable()->after('payment_link'); // Field for the expiration date of the link
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_link', 'expiration_date']); // Drops the fields if rolled back
        });
    }
};
