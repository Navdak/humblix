<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('team_members')
            ->where('name', 'UGOCHUKWU HUMBLE CHIEMELA')
            ->where('photo_path', 'images/uch-founder.png')
            ->update(['photo_path' => 'images/generated/careers/careers-engineers-inspecting-systems.jpg']);
    }

    public function down(): void
    {
        DB::table('team_members')
            ->where('name', 'UGOCHUKWU HUMBLE CHIEMELA')
            ->where('photo_path', 'images/generated/careers/careers-engineers-inspecting-systems.jpg')
            ->update(['photo_path' => 'images/uch-founder.png']);
    }
};
