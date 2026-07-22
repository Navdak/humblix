<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('articles')
            ->whereNotNull('video_embed_url')
            ->where('video_embed_url', 'like', 'https://www.youtube.com/embed/%')
            ->update([
                'video_embed_url' => DB::raw("REPLACE(video_embed_url, 'https://www.youtube.com/embed/', 'https://www.youtube-nocookie.com/embed/')"),
            ]);
    }

    public function down(): void
    {
        DB::table('articles')
            ->whereNotNull('video_embed_url')
            ->where('video_embed_url', 'like', 'https://www.youtube-nocookie.com/embed/%')
            ->update([
                'video_embed_url' => DB::raw("REPLACE(video_embed_url, 'https://www.youtube-nocookie.com/embed/', 'https://www.youtube.com/embed/')"),
            ]);
    }
};
