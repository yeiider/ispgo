<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('invoice_payments'))->pluck('name')->all();
            if (!in_array('invoice_payments_invoice_id_index', $existingIndexes)) {
                $table->index('invoice_id', 'invoice_payments_invoice_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('invoice_payments'))->pluck('name')->all();
            if (in_array('invoice_payments_invoice_id_index', $existingIndexes)) {
                $table->dropIndex('invoice_payments_invoice_id_index');
            }
        });
    }
};
