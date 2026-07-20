<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            if (! Schema::hasColumn('articles', 'newsletter_notified_at')) {
                $table->timestamp('newsletter_notified_at')->nullable()->after('published_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            if (Schema::hasColumn('articles', 'newsletter_notified_at')) {
                $table->dropColumn('newsletter_notified_at');
            }
        });
    }
};
