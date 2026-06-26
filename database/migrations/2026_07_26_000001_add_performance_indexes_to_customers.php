<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // FULLTEXT indexes for LIKE %search% queries (scopeSearch)
            // Allows: WHERE MATCH(first_name, last_name) AGAINST(? IN BOOLEAN MODE)
            $table->fullText(['first_name', 'last_name'], 'customers_name_fulltext');

            // FULLTEXT for identity document searches
            $table->fullText(['identity_document'], 'customers_identity_doc_fulltext');

            // Index for customer_status filtering
            $table->index('customer_status', 'customers_status_index');

            // Composite index for the router_filter global scope + status
            $table->index(['router_id', 'customer_status'], 'customers_router_status_index');

            // Index for sorting/filtering by created_at
            $table->index('created_at', 'customers_created_at_index');
        });

        // Add index for services.router_id (used in router_filter subquery)
        Schema::table('services', function (Blueprint $table) {
            $table->index(['router_id', 'customer_id'], 'services_router_customer_index');
            $table->index('service_status', 'services_status_index');
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
