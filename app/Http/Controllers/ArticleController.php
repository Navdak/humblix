<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\UchContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $activeCategory = array_key_exists((string) $category, Article::CATEGORIES) ? (string) $category : null;

        return view('articles.index', [
            'articles' => Article::published()
                ->category($activeCategory)
                ->latest('published_at')
                ->paginate(9)
                ->withQueryString(),
            'categories' => Article::CATEGORIES,
            'activeCategory' => $activeCategory,
        ]);
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);
        $seoImage = UchContent::imageUrl($article->featured_image_path);

        return view('articles.show', [
            'article' => $article->load('relatedLinks'),
            'latestArticles' => Article::published()->where('id','!=',$article->id)->latest('published_at')->take(4)->get(),
            'relatedArticles' => $this->relatedArticles($article),
            'seoImage' => $seoImage,
            'structuredData' => [[
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $article->title,
                'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 150),
                'articleSection' => $article->categoryLabel(),
                'image' => $seoImage,
                'datePublished' => optional($article->published_at)->toAtomString(),
                'dateModified' => optional($article->updated_at)->toAtomString(),
                'author' => [
                    '@type' => 'Organization',
                    'name' => 'HUMELIX LIMITED',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'HUMELIX LIMITED',
                ],
                'mainEntityOfPage' => route('articles.show', $article),
            ]],
        ]);
    }

    private function relatedArticles(Article $article)
    {
        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->categorySlug())
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($related->count() >= 3) {
            return $related;
        }

        $fallback = Article::published()
            ->where('id', '!=', $article->id)
            ->whereNotIn('id', $related->pluck('id'))
            ->latest('published_at')
            ->take(3 - $related->count())
            ->get();

        return $related->concat($fallback)->values();
    }
}
