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
            ['home', 'Home', 'HUMELIX SYSTEMS - HVAC, Solar, Electrical & Maintenance Solutions', 'Humelix Systems delivers HVAC, solar, electrical, maintenance, equipment supply and home appliance installation solutions for homes, businesses and industries.'],
            ['about', 'About', 'About HUMELIX SYSTEMS', 'Learn about Humelix Systems, a global engineering services company built around safety, precision, reliable delivery and disciplined aftercare.'],
            ['services', 'Services', 'Humelix Engineering Services', 'Explore Humelix HVAC installation, solar installation, electrical maintenance, equipment supply and home appliance installation services.'],
            ['industries', 'Industries', 'Industries Served by Humelix Systems', 'Humelix supports homes, estates, offices, factories, warehouses, hospitals, hotels, schools, retail spaces and data centres.'],
            ['projects', 'Projects', 'Humelix Project Case Studies', 'View selected Humelix Systems projects across HVAC, solar, electrical, maintenance and equipment supply work.'],
            ['safety', 'Safety', 'Humelix Safety Centre', 'Review Humelix Systems safety culture, controlled work practices, PPE expectations, risk assessment and safe handover approach.'],
            ['team', 'Team', 'Humelix Team', 'Meet the Humelix Systems engineering, operations and field support team.'],
            ['branches', 'Branches', 'Humelix Branches', 'Find published Humelix Systems branch and operating location information.'],
            ['resources', 'Resources', 'Humelix Engineering Resources', 'Read practical Humelix guides and articles for HVAC, solar, electrical, maintenance, safety and equipment decisions.'],
            ['careers', 'Careers', 'Careers at HUMELIX SYSTEMS', 'Explore Humelix Systems career openings and future opportunities in engineering operations and support.'],
            ['contact', 'Contact', 'Contact HUMELIX SYSTEMS', 'Request consultation, site inspection, quotation or support from Humelix Systems.'],
            ['equipment', 'Equipment', 'Humelix Vendor / Equipment Catalogue', 'Browse request-based Humelix equipment categories for HVAC, solar, electrical and home installation support.'],
            ['videos', 'Videos', 'Humelix Video Library', 'Watch Humelix Systems videos covering projects, services, field work, safety and equipment support.'],
            ['privacy-policy', 'Privacy Policy', 'Privacy Policy - HUMELIX SYSTEMS', 'Review how Humelix Systems handles enquiry, contact and website information.'],
            ['terms', 'Terms of Use', 'Terms of Use - HUMELIX SYSTEMS', 'Read the general terms for using the Humelix Systems website and requesting services.'],
            ['cookie-policy', 'Cookie Policy', 'Cookie Policy - HUMELIX SYSTEMS', 'Learn how Humelix Systems may use essential and analytics cookies on the website.'],
            ['accessibility', 'Accessibility Statement', 'Accessibility Statement - HUMELIX SYSTEMS', 'Read the Humelix Systems accessibility statement and contact pathway for website accessibility feedback.'],
        ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
