<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Branch;
use App\Models\Project;
use App\Support\UchContent;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect();
        $add = fn (string $url, mixed $lastmod = null, string $priority = '0.7') => $urls->push([
            'loc' => url($url),
            'lastmod' => $lastmod ? optional($lastmod)->toAtomString() : null,
            'priority' => $priority,
        ]);

        foreach (['/', '/about', '/services', '/industries', '/projects', '/safety', '/team', '/branches', '/resources', '/careers', '/contact', '/equipment', '/videos', '/privacy-policy', '/terms', '/cookie-policy', '/accessibility'] as $path) {
            $add($path, null, $path === '/' ? '1.0' : '0.8');
        }

        foreach (UchContent::serviceDivisions() as $service) {
            $add('/services/'.$service['slug'], null, '0.8');
        }

        foreach (UchContent::industries() as $industry) {
            $add('/industries/'.$industry['slug'], null, '0.7');
        }

        foreach (UchContent::safetyModules() as $topic) {
            $add('/safety/'.$topic['slug'], null, '0.6');
        }

        Project::where('status', 'published')->latest('updated_at')->get(['slug', 'updated_at'])->each(fn (Project $project) => $add('/projects/'.$project->slug, $project->updated_at, '0.7'));
        Article::published()->latest('updated_at')->get(['slug', 'updated_at'])->each(fn (Article $article) => $add('/resources/'.$article->slug, $article->updated_at, '0.7'));

        $latestBranch = Branch::where('is_published', true)->latest('updated_at')->value('updated_at');
        if ($latestBranch) {
            $urls = $urls->reject(fn ($url) => $url['loc'] === url('/branches'))->push(['loc' => url('/branches'), 'lastmod' => $latestBranch->toAtomString(), 'priority' => '0.8']);
        }

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
