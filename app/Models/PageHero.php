<?php

namespace App\Models;

use App\Support\UchContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PageHero extends Model
{
    protected $fillable = [
        'key',
        'label',
        'eyebrow',
        'title',
        'subtitle',
        'image_path',
        'fallback_image_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function defaults(): array
    {
        return [
            ['key' => 'home', 'label' => 'Home', 'eyebrow' => 'HUMELIX LIMITED', 'title' => 'Engineering Comfort. Powering Reliability.', 'subtitle' => 'HVAC, solar, electrical, maintenance and equipment solutions for residential, commercial and industrial clients worldwide.', 'fallback_image_path' => 'images/generated/home/home-hero-engineering.jpg'],
            ['key' => 'about', 'label' => 'About', 'eyebrow' => 'About Humelix', 'title' => 'Global engineering services founded in 2018.', 'subtitle' => 'HUMELIX LIMITED delivers HVAC, solar, electrical, maintenance, equipment/vendor support and home appliance installation for residential, commercial and industrial clients.', 'fallback_image_path' => 'images/generated/home/home-engineering-team-worksite.jpg'],
            ['key' => 'founder', 'label' => 'Founder', 'eyebrow' => 'Founder', 'title' => 'UGOCHUKWU HUMBLE CHIEMELA', 'subtitle' => 'Founder & Lead Engineer · Electrical/Electronics Engineering · Abia State University · 6+ years field practice.', 'fallback_image_path' => 'images/generated/careers/careers-engineers-inspecting-systems.jpg'],
            ['key' => 'services', 'label' => 'Services', 'eyebrow' => 'Humelix Services', 'title' => 'Engineering Service Divisions', 'subtitle' => 'Humelix provides HVAC, solar, electrical, maintenance, vendor/equipment and home appliance installation solutions for residential, commercial and industrial environments.', 'fallback_image_path' => 'images/generated/services/service-hvac-installation.jpg'],
            ['key' => 'industries', 'label' => 'Industries', 'eyebrow' => 'Industries', 'title' => 'Industries Served', 'subtitle' => 'Humelix supports residential, commercial, industrial, institutional and public-sector environments with practical engineering services.', 'fallback_image_path' => 'images/generated/industries/industry-offices.jpg'],
            ['key' => 'projects', 'label' => 'Projects', 'eyebrow' => 'Projects', 'title' => 'Engineering case studies and project proof.', 'subtitle' => 'Selected work across industrial, commercial, residential, institutional and public-sector environments.', 'fallback_image_path' => 'images/generated/projects/project-industrial-plant-neutral.jpg'],
            ['key' => 'safety', 'label' => 'Safety', 'eyebrow' => 'Safety Centre', 'title' => 'Safety Is Built Into Every Humelix Project', 'subtitle' => 'Safe engineering delivery for HVAC, solar, electrical, maintenance, equipment and home appliance projects.', 'fallback_image_path' => 'images/generated/safety/safety-ppe.jpg'],
            ['key' => 'team', 'label' => 'Team', 'eyebrow' => 'Team & Leadership', 'title' => 'Engineering teams, regional leads and support people.', 'subtitle' => 'Humelix is being structured around credible departments, field delivery, regional coordination and safety-first service culture.', 'fallback_image_path' => 'images/generated/careers/careers-engineers-inspecting-systems.jpg'],
            ['key' => 'branches', 'label' => 'Branches', 'eyebrow' => 'Branches', 'title' => 'Regional engineering support, prepared for branch-led service.', 'subtitle' => 'Our regional structure supports site response, project coordination and client service across multiple operating locations as Humelix continues to expand.', 'fallback_image_path' => 'images/generated/home/home-engineering-team-worksite.jpg'],
            ['key' => 'careers', 'label' => 'Careers', 'eyebrow' => 'Careers', 'title' => 'Build practical engineering work with Humelix.', 'subtitle' => 'As Humelix continues to expand globally, we continue to welcome disciplined engineers, technicians, safety-conscious installers, project coordinators and support professionals.', 'fallback_image_path' => 'images/generated/careers/careers-technicians-working.jpg'],
            ['key' => 'equipment', 'label' => 'Equipment / Vendor', 'eyebrow' => 'Equipment / Vendor', 'title' => 'Equipment supply and vendor quote foundation.', 'subtitle' => 'Humelix supports project teams with equipment sourcing, technical specification review and request-based vendor coordination.', 'fallback_image_path' => 'images/generated/home/home-service-preview-equipment.jpg'],
            ['key' => 'reviews', 'label' => 'Reviews', 'eyebrow' => 'Reviews', 'title' => 'Client feedback built on completed work.', 'subtitle' => 'Approved reviews from clients across our service environments.', 'fallback_image_path' => 'images/generated/careers/careers-team-collaboration.jpg'],
            ['key' => 'resources', 'label' => 'Resources', 'eyebrow' => 'Resources', 'title' => 'Practical engineering guides, maintenance advice and safety resources.', 'subtitle' => 'Publish HVAC, solar, electrical, safety, maintenance, vendor and company resources for clients planning reliable building systems.', 'fallback_image_path' => 'images/generated/safety/safety-toolbox-talks.jpg'],
            ['key' => 'videos', 'label' => 'Videos', 'eyebrow' => 'Video Library', 'title' => 'Humelix Video Library', 'subtitle' => 'Short field, project, product, safety and service demonstration videos from HUMELIX LIMITED.', 'fallback_image_path' => 'images/generated/careers/careers-technicians-working.jpg'],
            ['key' => 'contact', 'label' => 'Contact', 'eyebrow' => 'Contact Humelix', 'title' => 'Request a service consultation.', 'subtitle' => 'Tell us what you need, where the project is located and how you prefer to be contacted. We will generate a reference number for your request.', 'fallback_image_path' => 'images/generated/home/home-engineering-team-worksite.jpg'],
            ['key' => 'privacy-policy', 'label' => 'Privacy Policy', 'eyebrow' => 'Privacy', 'title' => 'Privacy Policy', 'subtitle' => 'How HUMELIX LIMITED handles website, enquiry and contact information.', 'fallback_image_path' => 'images/generated/equipment/equipment-cctv-security.jpg'],
            ['key' => 'terms', 'label' => 'Terms', 'eyebrow' => 'Terms', 'title' => 'Terms & Conditions', 'subtitle' => 'The terms that guide use of the HUMELIX LIMITED website and enquiry channels.', 'fallback_image_path' => 'images/generated/services/service-electrical-maintenance.jpg'],
            ['key' => 'cookie-policy', 'label' => 'Cookie Policy', 'eyebrow' => 'Cookies', 'title' => 'Cookie Policy', 'subtitle' => 'How this website may use cookies and similar technologies.', 'fallback_image_path' => 'images/generated/equipment/equipment-cctv-security.jpg'],
            ['key' => 'accessibility', 'label' => 'Accessibility', 'eyebrow' => 'Inclusive Access', 'title' => 'Accessibility Statement', 'subtitle' => 'HUMELIX LIMITED aims to make this website clear, navigable and usable for as many visitors as possible.', 'fallback_image_path' => 'images/generated/safety/safety-safe-handover.jpg'],
        ];
    }

    public static function seedDefaults(): void
    {
        foreach (self::defaults() as $index => $hero) {
            self::query()->updateOrCreate(
                ['key' => $hero['key']],
                $hero + ['is_active' => true, 'sort_order' => ($index + 1) * 10],
            );
        }
    }

    public static function resolve(?string $key, array $fallback = []): ?self
    {
        $key = $key ? trim($key) : null;
        try {
            $hero = $key ? self::query()->where('key', $key)->where('is_active', true)->first() : null;
        } catch (\Throwable) {
            $hero = null;
        }

        if ($hero) {
            return $hero;
        }

        if ($fallback === []) {
            return null;
        }

        return new self($fallback + ['key' => $key ?: 'dynamic', 'is_active' => true]);
    }

    public static function keyFromRequest(?Request $request = null): ?string
    {
        $request ??= request();
        $routeName = (string) $request->route()?->getName();

        return match ($routeName) {
            'home' => 'home',
            'about' => 'about',
            'founder' => 'founder',
            'services.index' => 'services',
            'industries.index', 'sectors.index' => 'industries',
            'projects.index' => 'projects',
            'safety' => 'safety',
            'team.index' => 'team',
            'branches.index' => 'branches',
            'careers.index' => 'careers',
            'equipment.index' => 'equipment',
            'reviews.index' => 'reviews',
            'articles.index' => 'resources',
            'videos.index' => 'videos',
            'contact' => 'contact',
            'legal.show' => (string) $request->route('page'),
            default => null,
        };
    }

    public function imagePath(): ?string
    {
        return $this->image_path ?: $this->fallback_image_path;
    }

    public function imageUrl(): ?string
    {
        return UchContent::imageUrl($this->image_path, $this->fallback_image_path);
    }

    public function hasUploadedImage(): bool
    {
        return filled($this->image_path) && ! str_starts_with((string) $this->image_path, 'images/');
    }
}
