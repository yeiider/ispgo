<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add database indexes to eliminate N+1 query performance bottlenecks.
 *
 * These indexes cover:
 *  - Foreign key columns used in eager-loaded relationships (customer_id,
 *    service_id, plan_id, router_id) so JOIN / IN-list lookups are O(log n).
 *  - Frequently filtered enum columns (service_status, customer_status,
 *    status on invoices) used in WHERE clauses across GraphQL queries.
 *  - created_at on the three main tables for ORDER BY / date-range filters.
 *
 * Laravel's foreignId() helper already creates an index on the FK column for
 * the addresses table, but the original migrations for services, invoices, and
 * customers used raw unsignedBigInteger() without explicit indexes, so we add
 * them here.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── services ────────────────────────────────────────────────────────
        Schema::table('services', function (Blueprint $table) {
            // customer_id is the most-queried FK (customer → services eager load)
            if (!$this->indexExists('services', 'services_customer_id_index')) {
                $table->index('customer_id', 'services_customer_id_index');
            }
            // plan_id FK used when eager-loading service.plan
            if (!$this->indexExists('services', 'services_plan_id_index')) {
                $table->index('plan_id', 'services_plan_id_index');
            }
            // router_id FK used in global scope filter and eager-loading service.router
            if (!$this->indexExists('services', 'services_router_id_index')) {
                $table->index('router_id', 'services_router_id_index');
            }
            // service_status used in WHERE filters on ServiceQuery and CustomerQuery
            if (!$this->indexExists('services', 'services_service_status_index')) {
                $table->index('service_status', 'services_service_status_index');
            }
            // created_at used for ORDER BY and date-range filters
            if (!$this->indexExists('services', 'services_created_at_index')) {
                $table->index('created_at', 'services_created_at_index');
            }
        });

        // ── invoices ─────────────────────────────────────────────────────────
        Schema::table('invoices', function (Blueprint $table) {
            // customer_id FK — most critical: eager-load invoice.customer and
            // the global scope subquery (customer → services → invoices)
            if (!$this->indexExists('invoices', 'invoices_customer_id_index')) {
                $table->index('customer_id', 'invoices_customer_id_index');
            }
            // service_id FK — eager-load invoice.service
            if (!$this->indexExists('invoices', 'invoices_service_id_index')) {
                $table->index('service_id', 'invoices_service_id_index');
            }
            // status used in WHERE filters (billing_status, unpaid checks)
            if (!$this->indexExists('invoices', 'invoices_status_index')) {
                $table->index('status', 'invoices_status_index');
            }
            // created_at used for ORDER BY and date-range filters
            if (!$this->indexExists('invoices', 'invoices_created_at_index')) {
                $table->index('created_at', 'invoices_created_at_index');
            }
            // Composite index for the common "unpaid invoices per customer" query
            // used in billing_status filter and whereHas checks
            if (!$this->indexExists('invoices', 'invoices_customer_id_status_index')) {
                $table->index(['customer_id', 'status'], 'invoices_customer_id_status_index');
            }
        });

        // ── customers ────────────────────────────────────────────────────────
        Schema::table('customers', function (Blueprint $table) {
            // customer_status used in WHERE filters on CustomerQuery
            if (!$this->indexExists('customers', 'customers_customer_status_index')) {
                $table->index('customer_status', 'customers_customer_status_index');
            }
            // created_at used for ORDER BY and date-range filters
            if (!$this->indexExists('customers', 'customers_created_at_index')) {
                $table->index('created_at', 'customers_created_at_index');
            }
            // router_id FK used in global scope filter
            if (!$this->indexExists('customers', 'customers_router_id_index')) {
                $table->index('router_id', 'customers_router_id_index');
            }
        });

        // ── invoice_payments ─────────────────────────────────────────────────
        Schema::table('invoice_payments', function (Blueprint $table) {
            // invoice_id FK — eager-load invoice.payments
            if (!$this->indexExists('invoice_payments', 'invoice_payments_invoice_id_index')) {
                $table->index('invoice_id', 'invoice_payments_invoice_id_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_customer_id_index');
            $table->dropIndex('services_plan_id_index');
            $table->dropIndex('services_router_id_index');
            $table->dropIndex('services_service_status_index');
            $table->dropIndex('services_created_at_index');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_customer_id_index');
            $table->dropIndex('invoices_service_id_index');
            $table->dropIndex('invoices_status_index');
            $table->dropIndex('invoices_created_at_index');
            $table->dropIndex('invoices_customer_id_status_index');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_customer_status_index');
            $table->dropIndex('customers_created_at_index');
            $table->dropIndex('customers_router_id_index');
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropIndex('invoice_payments_invoice_id_index');
        });
    }

    /**
     * Check whether a named index already exists on a table.
     * Prevents duplicate-index errors when running migrations on a database
     * that already has some indexes created manually.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $dbName     = $connection->getDatabaseName();

        $count = $connection->table('information_schema.statistics')
            ->where('table_schema', $dbName)
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->count();

        return $count > 0;
    }
};
