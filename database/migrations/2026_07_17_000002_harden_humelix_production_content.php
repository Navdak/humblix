<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceBrand('HUMELIX SYSTEMS', 'HUMELIX LIMITED');
        $this->replaceBrand('UCH SYSTEMS', 'HUMELIX LIMITED');
    }

    public function down(): void
    {
        // Intentionally not reversing production brand hardening.
    }

    private function replaceBrand(string $from, string $to): void
    {
        foreach ($this->tables() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)
                    ->where($column, 'like', "%{$from}%")
                    ->update([
                        $column => DB::raw("REPLACE({$column}, ".DB::getPdo()->quote($from).', '.DB::getPdo()->quote($to).')'),
                    ]);
            }
        }
    }

    private function tables(): array
    {
        return [
            'site_settings' => ['value'],
            'seo_settings' => ['page_label', 'meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description'],
            'articles' => ['title', 'excerpt', 'content'],
            'projects' => ['title', 'challenge', 'solution', 'result', 'outcome', 'client_testimonial'],
            'team_members' => ['bio'],
            'reviews' => ['comment', 'admin_response'],
            'videos' => ['title', 'caption', 'description', 'seo_description'],
            'enquiries' => ['message', 'notes'],
        ];
    }
};
