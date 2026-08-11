<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure amount field has default value of 0
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0)->change();
        });

        // Update existing NULL values to 0
        DB::statement('UPDATE invoices SET amount = 0 WHERE amount IS NULL');

        // Update amount for paid invoices that might have incorrect amount values
        // For paid invoices, amount should equal total if not already set
        DB::statement('
            UPDATE invoices
            SET amount = total
            WHERE status = "paid"
            AND (amount = 0 OR amount IS NULL)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse, keeping default value is safe
    }
};
