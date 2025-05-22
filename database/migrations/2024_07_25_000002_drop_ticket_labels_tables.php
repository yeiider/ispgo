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
        // Drop the pivot table first to avoid foreign key constraints
        Schema::dropIfExists('ticket_label');

        // Then drop the main table
        Schema::dropIfExists('ticket_labels');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the labels table
        Schema::create('ticket_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#3498db'); // Default color for labels
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Recreate the pivot table
        Schema::create('ticket_label', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('ticket_label_id');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('ticket_label_id')->references('id')->on('ticket_labels')->onDelete('cascade');

            // Ensure a label can only be added once to a ticket
            $table->unique(['ticket_id', 'ticket_label_id']);
        });
    }
};
