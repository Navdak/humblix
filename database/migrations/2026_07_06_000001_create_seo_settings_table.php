<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('page_label');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->boolean('noindex')->default(false)->index();
            $table->boolean('nofollow')->default(false);
            $table->json('structured_data_json')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();
        DB::table('seo_settings')->insert(array_map(fn (array $page): array => [
            'page_key' => $page[0],
            'page_label' => $page[1],
            'meta_title' => $page[2],
            'meta_description' => $page[3],
            'created_at' => $now,
            'updated_at' => $now,
        ], [
            ['home', 'Home', 'HUMELIX LIMITED - HVAC, Solar, Electrical & Maintenance Solutions', 'Humelix Limited delivers HVAC, solar, electrical, maintenance, equipment supply and home appliance installation solutions for homes, businesses and industries.'],
            ['about', 'About', 'About HUMELIX LIMITED', 'Learn about Humelix Limited, a global engineering services company built around safety, precision, reliable delivery and disciplined aftercare.'],
            ['services', 'Services', 'Humelix Engineering Services', 'Explore Humelix HVAC installation, solar installation, electrical maintenance, equipment supply and home appliance installation services.'],
            ['industries', 'Industries', 'Industries Served by Humelix Limited', 'Humelix supports homes, estates, offices, factories, warehouses, hospitals, hotels, schools, retail spaces and data centres.'],
            ['projects', 'Projects', 'Humelix Project Case Studies', 'View selected Humelix Limited projects across HVAC, solar, electrical, maintenance and equipment supply work.'],
            ['safety', 'Safety', 'Humelix Safety Centre', 'Review Humelix Limited safety culture, controlled work practices, PPE expectations, risk assessment and safe handover approach.'],
            ['team', 'Team', 'Humelix Team', 'Meet the Humelix Limited engineering, operations and field support team.'],
            ['branches', 'Branches', 'Humelix Branches', 'Find published Humelix Limited branch and operating location information.'],
            ['resources', 'Resources', 'Humelix Engineering Resources', 'Read practical Humelix guides and articles for HVAC, solar, electrical, maintenance, safety and equipment decisions.'],
            ['careers', 'Careers', 'Careers at HUMELIX LIMITED', 'Explore Humelix Limited career openings and future opportunities in engineering operations and support.'],
            ['contact', 'Contact', 'Contact HUMELIX LIMITED', 'Request consultation, site inspection, quotation or support from Humelix Limited.'],
            ['equipment', 'Equipment', 'Humelix Vendor / Equipment Catalogue', 'Browse request-based Humelix equipment categories for HVAC, solar, electrical and home installation support.'],
            ['videos', 'Videos', 'Humelix Video Library', 'Watch Humelix Limited videos covering projects, services, field work, safety and equipment support.'],
            ['privacy-policy', 'Privacy Policy', 'Privacy Policy - HUMELIX LIMITED', 'Review how Humelix Limited handles enquiry, contact and website information.'],
            ['terms', 'Terms of Use', 'Terms of Use - HUMELIX LIMITED', 'Read the general terms for using the Humelix Limited website and requesting services.'],
            ['cookie-policy', 'Cookie Policy', 'Cookie Policy - HUMELIX LIMITED', 'Learn how Humelix Limited may use essential and analytics cookies on the website.'],
            ['accessibility', 'Accessibility Statement', 'Accessibility Statement - HUMELIX LIMITED', 'Read the Humelix Limited accessibility statement and contact pathway for website accessibility feedback.'],
        ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
