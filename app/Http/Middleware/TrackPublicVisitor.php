<?php

namespace App\Http\Middleware;

use App\Models\VisitorEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackPublicVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            try {
                VisitorEvent::create([
                    'visitor_hash' => $this->visitorHash($request),
                    'path' => '/'.ltrim($request->path(), '/'),
                    'route_name' => $request->route()?->getName(),
                    'referrer_host' => $this->referrerHost($request),
                    'device_type' => $this->deviceType((string) $request->userAgent()),
                    'user_agent_hash' => hash('sha256', (string) $request->userAgent()),
                    'created_at' => now(),
                ]);
            } catch (\Throwable) {
                // Analytics must never block the public website.
            }
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! Schema::hasTable('visitor_events')) {
            return false;
        }

        if (! $request->isMethod('GET') || $request->user() || $request->expectsJson()) {
            return false;
        }

        if ($response->getStatusCode() >= 400 || $request->is('admin') || $request->is('admin/*')) {
            return false;
        }

        return ! $this->isBot((string) $request->userAgent());
    }

    private function visitorHash(Request $request): string
    {
        return hash_hmac(
            'sha256',
            implode('|', [(string) $request->ip(), (string) $request->userAgent()]),
            (string) config('app.key')
        );
    }

    private function referrerHost(Request $request): ?string
    {
        $referrer = (string) $request->headers->get('referer', '');
        $host = parse_url($referrer, PHP_URL_HOST);

        return is_string($host) && $host !== $request->getHost() ? $host : null;
    }

    private function deviceType(string $userAgent): string
    {
        $agent = strtolower($userAgent);

        return match (true) {
            str_contains($agent, 'tablet') || str_contains($agent, 'ipad') => 'tablet',
            str_contains($agent, 'mobile') || str_contains($agent, 'android') || str_contains($agent, 'iphone') => 'mobile',
            default => 'desktop',
        };
    }

    private function isBot(string $userAgent): bool
    {
        $agent = strtolower($userAgent);

        foreach (['bot', 'crawl', 'spider', 'slurp', 'bingpreview', 'facebookexternalhit', 'whatsapp', 'telegrambot', 'preview'] as $needle) {
            if (str_contains($agent, $needle)) {
                return true;
            }
        }

        return false;
    }
}
