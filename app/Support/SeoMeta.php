<?php

namespace App\Support;

use App\Models\SeoSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeoMeta
{
    public static function forCurrentPage(array $fallbacks = []): array
    {
        $pageKey = $fallbacks['page_key'] ?? self::pageKeyFromRoute();
        $setting = self::setting($pageKey);

        $title = $setting?->meta_title ?: ($fallbacks['title'] ?? config('app.name', 'HUMELIX SYSTEMS'));
        $description = $setting?->meta_description ?: ($fallbacks['description'] ?? 'Humelix Systems provides HVAC, solar, electrical, maintenance and equipment solutions for residential, commercial and industrial clients.');
        $canonical = $setting?->canonical_url ?: url()->current();
        $ogTitle = $setting?->og_title ?: $title;
        $ogDescription = $setting?->og_description ?: $description;
        $twitterTitle = $setting?->twitter_title ?: $ogTitle;
        $twitterDescription = $setting?->twitter_description ?: $ogDescription;
        $fallbackImage = asset('images/generated/home/home-hero-engineering.jpg');
        $ogImage = $setting?->ogImageUrl() ? url($setting->ogImageUrl()) : ($fallbacks['image'] ?? $fallbackImage);
        $ogImage = Str::startsWith($ogImage, ['http://', 'https://']) ? $ogImage : url($ogImage);
        $twitterImage = $setting?->twitterImageUrl() ? url($setting->twitterImageUrl()) : $ogImage;
        $twitterImage = Str::startsWith($twitterImage, ['http://', 'https://']) ? $twitterImage : url($twitterImage);

        return [
            'page_key' => $pageKey,
            'title' => Str::limit($title, 70, ''),
            'description' => Str::limit($description, 170, ''),
            'keywords' => $setting?->meta_keywords,
            'canonical' => $canonical,
            'og_title' => Str::limit($ogTitle, 70, ''),
            'og_description' => Str::limit($ogDescription, 200, ''),
            'og_image' => $ogImage,
            'twitter_title' => Str::limit($twitterTitle, 70, ''),
            'twitter_description' => Str::limit($twitterDescription, 200, ''),
            'twitter_image' => $twitterImage,
            'robots' => self::robotsValue($setting),
            'structured_data' => array_values(array_filter([
                self::organizationSchema(),
                self::websiteSchema(),
                self::breadcrumbSchema(),
                $setting?->structured_data_json,
                ...($fallbacks['structured_data'] ?? []),
            ])),
        ];
    }

    public static function pageKeyFromRoute(): string
    {
        $route = request()->route()?->getName();

        return match (true) {
            $route === 'home' => 'home',
            $route === 'about', $route === 'founder' => 'about',
            str_starts_with((string) $route, 'services.') => 'services',
            str_starts_with((string) $route, 'industries.'), str_starts_with((string) $route, 'sectors.') => 'industries',
            str_starts_with((string) $route, 'projects.') => 'projects',
            $route === 'safety' || str_starts_with((string) $route, 'safety.') => 'safety',
            str_starts_with((string) $route, 'team.') => 'team',
            str_starts_with((string) $route, 'branches.') => 'branches',
            str_starts_with((string) $route, 'articles.') => 'resources',
            str_starts_with((string) $route, 'careers.') => 'careers',
            $route === 'contact' => 'contact',
            str_starts_with((string) $route, 'equipment.') => 'equipment',
            str_starts_with((string) $route, 'videos.') => 'videos',
            str_starts_with((string) $route, 'legal.') => (string) request()->route('page', 'privacy-policy'),
            default => 'home',
        };
    }

    private static function setting(?string $pageKey): ?SeoSetting
    {
        if (! $pageKey || ! Schema::hasTable('seo_settings')) {
            return null;
        }

        return Cache::remember("seo_setting_{$pageKey}", 600, fn () => SeoSetting::where('page_key', $pageKey)->first());
    }

    private static function robotsValue(?SeoSetting $setting): string
    {
        $directives = [
            $setting?->noindex ? 'noindex' : 'index',
            $setting?->nofollow ? 'nofollow' : 'follow',
        ];

        return implode(', ', $directives);
    }

    private static function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'HUMELIX SYSTEMS',
            'url' => url('/'),
            'email' => config('mail.from.address'),
            'description' => 'Global engineering services for HVAC, solar, electrical, maintenance, equipment supply and home appliance installation.',
        ];
    }

    private static function websiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'HUMELIX SYSTEMS',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/resources').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    private static function breadcrumbSchema(): ?array
    {
        $segments = collect(request()->segments())->filter()->values();

        if ($segments->isEmpty()) {
            return null;
        }

        $path = '';
        $items = [[
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => url('/'),
        ]];

        foreach ($segments as $index => $segment) {
            $path .= '/'.$segment;
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 2,
                'name' => Str::headline($segment),
                'item' => url($path),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
