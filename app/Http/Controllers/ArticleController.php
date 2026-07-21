<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Video;
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
        $articleSchema = [
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
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/brand/humelix-icon-512.png'),
                ],
            ],
            'mainEntityOfPage' => route('articles.show', $article),
        ];

        if ($article->hasArticleVideo()) {
            $articleSchema['video'] = $this->articleVideoSchema($article, $seoImage);
        }

        return view('articles.show', [
            'article' => $article->load('relatedLinks'),
            'latestArticles' => Article::published()->where('id','!=',$article->id)->latest('published_at')->take(4)->get(),
            'relatedArticles' => $this->relatedArticles($article),
            'seoImage' => $seoImage,
            'structuredData' => [$articleSchema],
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

    private function articleVideoSchema(Article $article, string $fallbackImage): array
    {
        $youtubeId = Video::youtubeVideoIdFromUrl((string) $article->video_url);

        return array_filter([
            '@type' => 'VideoObject',
            'name' => $article->video_title ?: $article->title,
            'description' => $article->video_caption ?: $article->excerpt,
            'thumbnailUrl' => $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg" : $fallbackImage,
            'uploadDate' => optional($article->published_at ?: $article->created_at)->toAtomString(),
            'embedUrl' => $article->video_embed_url,
            'contentUrl' => $article->articleVideoPlaybackKind() === 'video' ? $article->video_embed_url : null,
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'HUMELIX LIMITED',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/brand/humelix-icon-512.png'),
                ],
            ],
        ]);
    }
}
