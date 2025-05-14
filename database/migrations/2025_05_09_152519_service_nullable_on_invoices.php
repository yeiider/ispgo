<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('service_id')
                ->nullable()
                ->change();

            if (Schema::hasColumn('invoices', 'plan_id')) {
                $table->foreignId('plan_id')
                    ->nullable()
                    ->change();
            }
            $table->string('billing_period');

            $table->enum('state', ['draft', 'building', 'calculating', 'failed', 'issued'])->default('draft');
            $table->decimal('amount_before_discounts', 12, 2)->nullable();
            $table->decimal('tax_total', 12, 2)->nullable();
            $table->decimal('void_total', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('service_id')
                ->nullable(false)
                ->change();
            $table->dropColumn('state');
            $table->dropColumn('billing_period');
            $table->dropColumn('amount_before_discounts');
            $table->dropColumn('tax_total');
            $table->dropColumn('void_total');
            if (Schema::hasColumn('invoices', 'plan_id')) {
                $table->foreignId('plan_id')
                    ->nullable(false)
                    ->change();
            }
        });
    }
};
