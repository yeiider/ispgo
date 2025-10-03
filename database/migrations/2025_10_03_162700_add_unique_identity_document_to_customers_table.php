<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'identity_document')) {
                // Fallback in case the column doesn't exist (should exist based on earlier migration)
                $table->string('identity_document', 12)->after('document_type');
            }
            // Add a unique index on identity_document if not already present
            $table->unique('identity_document', 'customers_identity_document_unique');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_identity_document_unique');
        });
    }
};
