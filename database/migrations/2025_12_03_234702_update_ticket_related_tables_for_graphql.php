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
        // Update ticket_comments table
        Schema::table('ticket_comments', function (Blueprint $table) {
            // Rename content to comment if exists
            if (Schema::hasColumn('ticket_comments', 'content')) {
                $table->renameColumn('content', 'comment');
            }

            // Add is_internal column if not exists
            if (!Schema::hasColumn('ticket_comments', 'is_internal')) {
                $table->boolean('is_internal')->default(false)->after('comment');
            }
        });

        // Update ticket_attachments table
        Schema::table('ticket_attachments', function (Blueprint $table) {
            // Rename filename to file_name if exists
            if (Schema::hasColumn('ticket_attachments', 'filename')) {
                $table->renameColumn('filename', 'file_name');
            }

            // Rename uploaded_by to user_id if exists
            if (Schema::hasColumn('ticket_attachments', 'uploaded_by')) {
                $table->renameColumn('uploaded_by', 'user_id');
            }

            // Add file_type and file_size columns if not exist
            if (!Schema::hasColumn('ticket_attachments', 'file_type')) {
                $table->string('file_type')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('ticket_attachments', 'file_size')) {
                $table->integer('file_size')->nullable()->after('file_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_comments', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_comments', 'comment')) {
                $table->renameColumn('comment', 'content');
            }
            if (Schema::hasColumn('ticket_comments', 'is_internal')) {
                $table->dropColumn('is_internal');
            }
        });

        Schema::table('ticket_attachments', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_attachments', 'file_name')) {
                $table->renameColumn('file_name', 'filename');
            }
            if (Schema::hasColumn('ticket_attachments', 'user_id')) {
                $table->renameColumn('user_id', 'uploaded_by');
            }
            if (Schema::hasColumn('ticket_attachments', 'file_type')) {
                $table->dropColumn('file_type');
            }
            if (Schema::hasColumn('ticket_attachments', 'file_size')) {
                $table->dropColumn('file_size');
            }
        });
    }
};
