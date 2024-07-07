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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('issue_type');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->string('title');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->text('attachments')->nullable();
            $table->string('contact_method')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
