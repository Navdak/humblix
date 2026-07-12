<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('related_service')->nullable();
            $table->foreignId('related_project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('related_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('related_equipment_id')->nullable()->constrained('equipment_items')->nullOnDelete();
            $table->string('video_type')->default('external');
            $table->string('external_url')->nullable();
            $table->string('embed_url')->nullable();
            $table->string('uploaded_video_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
