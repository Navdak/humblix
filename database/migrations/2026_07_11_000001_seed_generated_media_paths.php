<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $projectImages = [
            'industrial-plant-lagos' => 'images/generated/projects/project-industrial-plant-neutral.jpg',
            'office-complex-abuja' => 'images/generated/projects/project-office-complex-neutral.jpg',
            'high-rise-building-dubai' => 'images/generated/projects/project-high-rise-cooling-neutral.jpg',
            'warehouse-facility-port-harcourt' => 'images/generated/projects/project-warehouse-ventilation-neutral.jpg',
        ];

        foreach ($projectImages as $slug => $path) {
            DB::table('projects')->where('slug', $slug)->whereNull('image_path')->update(['image_path' => $path]);
        }

        DB::table('articles')
            ->where('slug', 'how-to-maintain-commercial-ac-systems')
            ->whereNull('featured_image_path')
            ->update(['featured_image_path' => 'images/generated/services/service-hvac-installation.jpg']);

        DB::table('team_members')
            ->where('name', 'UGOCHUKWU HUMBLE CHIEMELA')
            ->whereNull('photo_path')
            ->update(['photo_path' => 'images/uch-founder.png']);

        $seoImages = [
            'home' => 'images/generated/home/home-hero-engineering.jpg',
            'about' => 'images/generated/home/home-engineering-team-worksite.jpg',
            'services' => 'images/generated/services/service-hvac-installation.jpg',
            'industries' => 'images/generated/industries/industry-factories.jpg',
            'projects' => 'images/generated/projects/project-industrial-plant-neutral.jpg',
            'safety' => 'images/generated/safety/safety-ppe.jpg',
            'team' => 'images/generated/careers/careers-team-collaboration.jpg',
            'resources' => 'images/generated/services/service-hvac-installation.jpg',
            'careers' => 'images/generated/careers/careers-technicians-working.jpg',
            'contact' => 'images/generated/home/home-engineering-team-worksite.jpg',
            'equipment' => 'images/generated/home/home-service-preview-equipment.jpg',
            'videos' => 'images/generated/home/home-engineering-team-worksite.jpg',
        ];

        foreach ($seoImages as $pageKey => $path) {
            DB::table('seo_settings')->where('page_key', $pageKey)->whereNull('og_image')->update(['og_image' => $path]);
            DB::table('seo_settings')->where('page_key', $pageKey)->whereNull('twitter_image')->update(['twitter_image' => $path]);
        }
    }

    public function down(): void
    {
        $projectPaths = [
            'images/generated/projects/project-industrial-plant-neutral.jpg',
            'images/generated/projects/project-office-complex-neutral.jpg',
            'images/generated/projects/project-high-rise-cooling-neutral.jpg',
            'images/generated/projects/project-warehouse-ventilation-neutral.jpg',
        ];

        DB::table('projects')->whereIn('image_path', $projectPaths)->update(['image_path' => null]);
        DB::table('articles')->where('featured_image_path', 'images/generated/services/service-hvac-installation.jpg')->update(['featured_image_path' => null]);
        DB::table('team_members')->where('photo_path', 'images/uch-founder.png')->update(['photo_path' => null]);

        $seoPaths = [
            'images/generated/home/home-hero-engineering.jpg',
            'images/generated/home/home-engineering-team-worksite.jpg',
            'images/generated/services/service-hvac-installation.jpg',
            'images/generated/industries/industry-factories.jpg',
            'images/generated/projects/project-industrial-plant-neutral.jpg',
            'images/generated/safety/safety-ppe.jpg',
            'images/generated/careers/careers-team-collaboration.jpg',
            'images/generated/careers/careers-technicians-working.jpg',
            'images/generated/home/home-service-preview-equipment.jpg',
        ];

        DB::table('seo_settings')->whereIn('og_image', $seoPaths)->update(['og_image' => null]);
        DB::table('seo_settings')->whereIn('twitter_image', $seoPaths)->update(['twitter_image' => null]);
    }
};
