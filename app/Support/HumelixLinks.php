<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class HumelixLinks
{
    public static function normalizeWebsiteUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            $url = 'https://'.$url;
        }

        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            return rtrim($url, '/');
        }

        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'];
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';

        return rtrim($scheme.'://'.$host.$port, '/');
    }

    public static function websiteUrl(): string
    {
        $configuredUrl = Cache::remember('humelix_company_website_url', 3600, function (): ?string {
            try {
                return SiteSetting::query()->where('key', 'company_website_url')->value('value');
            } catch (\Throwable) {
                return null;
            }
        });

        $url = self::normalizeWebsiteUrl((string) ($configuredUrl ?: config('app.url')));

        return rtrim($url ?: url('/'), '/');
    }

    public static function url(string $path = '/'): string
    {
        return self::websiteUrl().'/'.ltrim($path, '/');
    }

    public static function assetUrl(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return self::url($path);
    }
}
