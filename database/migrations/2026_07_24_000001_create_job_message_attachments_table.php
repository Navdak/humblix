<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_message_attachments')) {
            Schema::create('job_message_attachments', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('client_job_id')->constrained('client_jobs')->cascadeOnDelete();
                $table->foreignId('job_message_id')->constrained('job_messages')->cascadeOnDelete();
                $table->string('sender_type', 40)->index();
                $table->string('disk', 40)->default('local');
                $table->string('file_path');
                $table->string('original_name');
                $table->string('mime_type', 120)->nullable();
                $table->unsignedBigInteger('size_bytes')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_message_attachments');
    }
};
