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

        $titles = [
            'services' => 'Humelix Limited Engineering Services',
            'industries' => 'Industries Served by Humelix Limited',
            'projects' => 'Humelix Limited Project Case Studies',
            'safety' => 'Humelix Limited Safety Centre',
            'team' => 'Humelix Limited Team',
            'branches' => 'Humelix Limited Branches',
            'resources' => 'Humelix Limited Engineering Resources',
            'equipment' => 'Humelix Limited Vendor / Equipment Catalogue',
            'videos' => 'Humelix Limited Video Library',
        ];

        foreach ($titles as $pageKey => $title) {
            DB::table('seo_settings')->where('page_key', $pageKey)->update(['meta_title' => $title]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('seo_settings')) {
            return;
        }

        $titles = [
            'services' => 'Humelix Engineering Services',
            'industries' => 'Industries Served by Humelix Limited',
            'projects' => 'Humelix Project Case Studies',
            'safety' => 'Humelix Safety Centre',
            'team' => 'Humelix Team',
            'branches' => 'Humelix Branches',
            'resources' => 'Humelix Engineering Resources',
            'equipment' => 'Humelix Vendor / Equipment Catalogue',
            'videos' => 'Humelix Video Library',
        ];

        foreach ($titles as $pageKey => $title) {
            DB::table('seo_settings')->where('page_key', $pageKey)->update(['meta_title' => $title]);
        }
    }
};
