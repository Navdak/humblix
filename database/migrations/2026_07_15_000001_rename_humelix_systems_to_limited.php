<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceBrand('HUMELIX SYSTEMS', 'HUMELIX LIMITED');
        $this->replaceBrand('Humelix Systems', 'Humelix Limited');
        $this->replaceBrand('humelix systems', 'humelix limited');
    }

    public function down(): void
    {
        $this->replaceBrand('HUMELIX LIMITED', 'HUMELIX SYSTEMS');
        $this->replaceBrand('Humelix Limited', 'Humelix Systems');
        $this->replaceBrand('humelix limited', 'humelix systems');
    }

    private function replaceBrand(string $from, string $to): void
    {
        $tables = [
            'site_settings' => ['value'],
            'seo_settings' => ['meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description'],
            'users' => ['name'],
            'projects' => ['challenge', 'solution', 'result', 'equipment_used', 'safety_controls'],
            'reviews' => ['comment'],
            'articles' => ['title', 'excerpt', 'content'],
        ];

        foreach ($tables as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)->where($column, 'like', "%{$from}%")->update([
                    $column => DB::raw("REPLACE({$column}, ".DB::getPdo()->quote($from).', '.DB::getPdo()->quote($to).')'),
                ]);
            }
        }
    }
};
