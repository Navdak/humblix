<?php

use App\Support\UchContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safety_topics', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('excerpt', 500);
            $table->json('summary_points')->nullable();
            $table->longText('content');
            $table->string('image_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_embed_url')->nullable();
            $table->string('video_title')->nullable();
            $table->string('video_caption', 500)->nullable();
            $table->string('video_placement')->default('end');
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('status')->default('published');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        $now = now();

        foreach (UchContent::safetyModules() as $index => $module) {
            DB::table('safety_topics')->insert([
                'title' => $module['title'],
                'slug' => $module['slug'] ?? Str::slug($module['title']),
                'category' => 'Safety Framework',
                'excerpt' => $module['description'],
                'summary_points' => json_encode($module['summary'] ?? []),
                'content' => '<p>'.e($module['detail'] ?? $module['description']).'</p>',
                'image_path' => $module['image'] ?? UchContent::safetyImage($module['title']),
                'video_placement' => 'end',
                'cta_label' => 'Request Site Inspection',
                'cta_url' => '/contact?service=Maintenance',
                'status' => 'published',
                'sort_order' => ($index + 1) * 10,
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('safety_topics');
    }
};
