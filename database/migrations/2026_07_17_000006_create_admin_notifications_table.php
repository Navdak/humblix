<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('permission')->nullable()->index();
            $table->string('action_url')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_notification_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at');
            $table->timestamps();
            $table->unique(['admin_notification_id', 'user_id'], 'admin_notification_reads_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notification_reads');
        Schema::dropIfExists('admin_notifications');
    }
};
