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
        // Create the labels table for predefined categories
        Schema::create('ticket_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#3498db'); // Default color for labels
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create the pivot table to associate labels with tickets
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

        // Insert default labels
        DB::table('ticket_labels')->insert([
            ['name' => 'Technical Support', 'color' => '#e74c3c', 'description' => 'Issues related to technical support', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finance', 'color' => '#2ecc71', 'description' => 'Issues related to billing and payments', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Password Change', 'color' => '#f39c12', 'description' => 'Requests for password changes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Installation', 'color' => '#9b59b6', 'description' => 'New service installations', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ISP Support', 'color' => '#1abc9c', 'description' => 'General ISP support issues', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_label');
        Schema::dropIfExists('ticket_labels');
    }
};
