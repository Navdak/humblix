<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seo_settings')) {
            return;
        }

        $temporaryOgImage = 'images/brand/humelix-og-image.jpg';
        $previousFallbackImage = 'images/generated/home/home-hero-engineering.jpg';
        $now = now();

        DB::table('seo_settings')->updateOrInsert(
            ['page_key' => 'reviews'],
            [
                'page_label' => 'Reviews',
                'meta_title' => 'Humelix Limited Client Reviews',
                'meta_description' => 'Read approved client reviews and feedback for Humelix Limited engineering, maintenance, vendor and installation work.',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        );

        DB::table('seo_settings')
            ->whereNull('og_image')
            ->orWhere('og_image', $previousFallbackImage)
            ->update(['og_image' => $temporaryOgImage, 'updated_at' => $now]);

        DB::table('seo_settings')
            ->whereNull('twitter_image')
            ->orWhere('twitter_image', $previousFallbackImage)
            ->update(['twitter_image' => $temporaryOgImage, 'updated_at' => $now]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('seo_settings')) {
            return;
        }

        DB::table('seo_settings')->where('page_key', 'reviews')->delete();
    }
};
