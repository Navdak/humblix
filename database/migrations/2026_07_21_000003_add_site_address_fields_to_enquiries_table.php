<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('enquiries')) {
            return;
        }

        Schema::table('enquiries', function (Blueprint $table): void {
            if (! Schema::hasColumn('enquiries', 'site_address')) {
                $table->text('site_address')->nullable()->after('project_location');
            }

            if (! Schema::hasColumn('enquiries', 'confirmed_site_address')) {
                $table->text('confirmed_site_address')->nullable()->after('site_address');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('enquiries')) {
            return;
        }

        Schema::table('enquiries', function (Blueprint $table): void {
            if (Schema::hasColumn('enquiries', 'confirmed_site_address')) {
                $table->dropColumn('confirmed_site_address');
            }

            if (Schema::hasColumn('enquiries', 'site_address')) {
                $table->dropColumn('site_address');
            }
        });
    }
};
