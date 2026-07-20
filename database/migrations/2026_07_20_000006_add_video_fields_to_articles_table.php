<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            if (! Schema::hasColumn('articles', 'video_url')) {
                $table->string('video_url')->nullable()->after('pdf_path');
            }

            if (! Schema::hasColumn('articles', 'video_embed_url')) {
                $table->string('video_embed_url')->nullable()->after('video_url');
            }

            if (! Schema::hasColumn('articles', 'video_title')) {
                $table->string('video_title')->nullable()->after('video_embed_url');
            }

            if (! Schema::hasColumn('articles', 'video_caption')) {
                $table->string('video_caption', 300)->nullable()->after('video_title');
            }

            if (! Schema::hasColumn('articles', 'video_placement')) {
                $table->string('video_placement', 40)->default('end')->after('video_caption');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            foreach (['video_url', 'video_embed_url', 'video_title', 'video_caption', 'video_placement'] as $column) {
                if (Schema::hasColumn('articles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
