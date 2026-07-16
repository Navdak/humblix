<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\UchContent;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index', ['articles' => Article::published()->latest('published_at')->paginate(9)]);
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);
        $seoImage = UchContent::imageUrl($article->featured_image_path);

        return view('articles.show', [
            'article' => $article->load('relatedLinks'),
            'latestArticles' => Article::published()->where('id','!=',$article->id)->latest('published_at')->take(4)->get(),
            'seoImage' => $seoImage,
            'structuredData' => [[
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $article->title,
                'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 150),
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
}
