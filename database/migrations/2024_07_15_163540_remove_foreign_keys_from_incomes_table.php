<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeysFromIncomesTable extends Migration
{
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['invoice_id']);
        });
    }

    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('invoice_id')->constrained();
        });
    }
}
