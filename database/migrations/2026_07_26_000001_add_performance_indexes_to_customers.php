<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $existingIndexes = collect(Schema::getIndexes('customers'))->pluck('name')->toArray();
        Schema::table('customers', function (Blueprint $table) use ($existingIndexes) {
            // FULLTEXT indexes for LIKE %search% queries (scopeSearch)
            if (!in_array('customers_name_fulltext', $existingIndexes)) {
                $table->fullText(['first_name', 'last_name'], 'customers_name_fulltext');
            }

            // FULLTEXT for identity document searches
            if (!in_array('customers_identity_doc_fulltext', $existingIndexes)) {
                $table->fullText(['identity_document'], 'customers_identity_doc_fulltext');
            }

            // Index for customer_status filtering
            if (!in_array('customers_status_index', $existingIndexes)) {
                $table->index('customer_status', 'customers_status_index');
            }

            // Composite index for the router_filter global scope + status
            if (!in_array('customers_router_status_index', $existingIndexes)) {
                $table->index(['router_id', 'customer_status'], 'customers_router_status_index');
            }

            // Index for sorting/filtering by created_at
            if (!in_array('customers_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'customers_created_at_index');
            }
        });

        // Add index for services.router_id (used in router_filter subquery)
        $existingServicesIndexes = collect(Schema::getIndexes('services'))->pluck('name')->toArray();
        Schema::table('services', function (Blueprint $table) use ($existingServicesIndexes) {
            if (!in_array('services_router_customer_index', $existingServicesIndexes)) {
                $table->index(['router_id', 'customer_id'], 'services_router_customer_index');
            }
            if (!in_array('services_status_index', $existingServicesIndexes)) {
                $table->index('service_status', 'services_status_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            try {
                $table->dropFullText('customers_name_fulltext');
            } catch (\Throwable $e) {}
            try {
                $table->dropFullText('customers_identity_doc_fulltext');
            } catch (\Throwable $e) {}
            try {
                $table->dropIndex('customers_status_index');
            } catch (\Throwable $e) {}
            try {
                $table->dropIndex('customers_router_status_index');
            } catch (\Throwable $e) {}
            try {
                $table->dropIndex('customers_created_at_index');
            } catch (\Throwable $e) {}
        });

        Schema::table('services', function (Blueprint $table) {
            try {
                $table->dropIndex('services_router_customer_index');
            } catch (\Throwable $e) {}
            try {
                $table->dropIndex('services_status_index');
            } catch (\Throwable $e) {}
        });
    }
};

