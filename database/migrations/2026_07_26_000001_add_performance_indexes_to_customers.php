<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $dbName = DB::connection()->getDatabaseName();

        // Get existing indexes for customers and services tables
        $customersIndexes = collect(DB::select(
            "SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
            [$dbName, 'customers']
        ))->pluck('INDEX_NAME')->unique()->values()->all();

        $servicesIndexes = collect(DB::select(
            "SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
            [$dbName, 'services']
        ))->pluck('INDEX_NAME')->unique()->values()->all();

        Schema::table('customers', function (Blueprint $table) use ($customersIndexes) {
            // FULLTEXT indexes for LIKE %search% queries (scopeSearch)
            if (!in_array('customers_name_fulltext', $customersIndexes)) {
                $table->fullText(['first_name', 'last_name'], 'customers_name_fulltext');
            }
            if (!in_array('customers_identity_doc_fulltext', $customersIndexes)) {
                $table->fullText(['identity_document'], 'customers_identity_doc_fulltext');
            }
            if (!in_array('customers_status_index', $customersIndexes)) {
                $table->index('customer_status', 'customers_status_index');
            }
            if (!in_array('customers_router_status_index', $customersIndexes)) {
                $table->index(['router_id', 'customer_status'], 'customers_router_status_index');
            }
            if (!in_array('customers_created_at_index', $customersIndexes)) {
                $table->index('created_at', 'customers_created_at_index');
            }
        });

        Schema::table('services', function (Blueprint $table) use ($servicesIndexes) {
            if (!in_array('services_router_customer_index', $servicesIndexes)) {
                $table->index(['router_id', 'customer_id'], 'services_router_customer_index');
            }
            if (!in_array('services_status_index', $servicesIndexes)) {
                $table->index('service_status', 'services_status_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropFullText('customers_name_fulltext');
            $table->dropFullText('customers_identity_doc_fulltext');
            $table->dropIndex('customers_status_index');
            $table->dropIndex('customers_router_status_index');
            $table->dropIndex('customers_created_at_index');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_router_customer_index');
            $table->dropIndex('services_status_index');
        });
    }
};
