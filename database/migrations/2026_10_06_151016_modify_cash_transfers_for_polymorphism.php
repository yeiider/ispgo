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
        Schema::table('cash_transfers', function (Blueprint $table) {
            // Polymorphic sender
            $table->nullableMorphs('sender');
            
            // Polymorphic receiver
            $table->nullableMorphs('receiver');
            
            // Recepciones parciales
            $table->decimal('received_amount', 10, 2)->nullable()->after('amount');
            $table->text('discrepancy_note')->nullable()->after('status');
            
            // Viejo esquema pasa a nullable
            $table->unsignedBigInteger('sender_cash_register_id')->nullable()->change();
            $table->unsignedBigInteger('receiver_cash_register_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->dropMorphs('sender');
            $table->dropMorphs('receiver');
            $table->dropColumn(['received_amount', 'discrepancy_note']);
        });
    }
};
