<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewArticlePublishedMail;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use App\Models\Video;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ArticleController extends Controller
{
    public function index() { return view('admin.articles.index', ['articles' => Article::latest()->paginate(15)]); }
    public function create() { return view('admin.articles.create', ['article' => new Article(), 'categories' => Article::CATEGORIES, 'videoPlacements' => Article::VIDEO_PLACEMENTS]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->storePdfIfUploaded($request, $data);
        $article = Article::create($data);
        $this->syncLinks($request, $article);
        $this->sendNewsletterForPublishedArticle($article->fresh());
        return redirect()->route('admin.articles.index')->with('success','Article created.');
    }
    public function edit(Article $article) { return view('admin.articles.edit', ['article'=>$article->load('relatedLinks'), 'categories' => Article::CATEGORIES, 'videoPlacements' => Article::VIDEO_PLACEMENTS]); }
    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request);
        $this->updatePdfIfRequested($request, $article, $data);
        $article->update($data);
        $this->syncLinks($request, $article);
        $this->sendNewsletterForPublishedArticle($article->fresh());
        return back()->with('success','Article updated.');
    }
    public function destroy(Article $article): RedirectResponse
    {
        $this->deleteUploadedPdf($article);
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success','Article deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'=>['required','string','max:180'], 'slug'=>['nullable','string','max:220'], 'excerpt'=>['required','string','max:160'],
            'category'=>['required','string','in:'.implode(',', array_keys(Article::CATEGORIES))],
            'content'=>['required','string'], 'status'=>['required','in:draft,published'], 'featured_image'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'pdf_attachment'=>['nullable','file','mimes:pdf','max:10240'],
            'remove_pdf'=>['nullable','boolean'],
            'video_url'=>['nullable','url','max:255'],
            'video_title'=>['nullable','string','max:160'],
            'video_caption'=>['nullable','string','max:300'],
            'video_placement'=>['nullable','string','in:'.implode(',', array_keys(Article::VIDEO_PLACEMENTS))],
        ]);

        $this->normalizeArticleVideoData($data);

        $wordCount = $this->wordCount($data['content']);

        if ($wordCount > Article::MAX_WORD_COUNT) {
            throw ValidationException::withMessages([
                'content' => 'This article is too long. The maximum is '.number_format(Article::MAX_WORD_COUNT).' words. Please split it into multiple focused articles or upload the full version as a PDF guide.',
            ]);
        }

        $data['content'] = HtmlSanitizer::clean($data['content']);
        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['author_id'] = auth()->id();
        if ($request->hasFile('featured_image')) $data['featured_image_path'] = $request->file('featured_image')->store('articles','public');
        unset($data['featured_image'], $data['pdf_attachment'], $data['remove_pdf']);
        return $data;
    }

    private function normalizeArticleVideoData(array &$data): void
    {
        $videoUrl = trim((string) ($data['video_url'] ?? ''));
        $data['video_placement'] = $data['video_placement'] ?? 'end';

        if ($videoUrl === '') {
            $data['video_url'] = null;
            $data['video_embed_url'] = null;
            $data['video_title'] = null;
            $data['video_caption'] = null;

            return;
        }

        $embedUrl = $this->safeArticleVideoEmbedUrl($videoUrl);

        if (! $embedUrl) {
            throw ValidationException::withMessages([
                'video_url' => 'Use a supported YouTube, YouTube Shorts, Vimeo, MP4, or WebM video URL.',
            ]);
        }

        $data['video_url'] = $videoUrl;
        $data['video_embed_url'] = $embedUrl;
    }

    private function safeArticleVideoEmbedUrl(string $url): ?string
    {
        $youtubeId = Video::youtubeVideoIdFromUrl($url);

        if ($youtubeId) {
            return "https://www.youtube.com/embed/{$youtubeId}";
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if (str_contains($host, 'vimeo.com')) {
            $segments = array_values(array_filter(explode('/', $path)));
            $vimeoId = end($segments);

            return is_string($vimeoId) && preg_match('/^\d+$/', $vimeoId)
                ? "https://player.vimeo.com/video/{$vimeoId}"
                : null;
        }

        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'webm'], true) ? $url : null;
    }

    private function wordCount(string $html): int
    {
        $text = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if ($text === '') {
            return 0;
        }

        preg_match_all('/[\p{L}\p{N}]+(?:[\'-][\p{L}\p{N}]+)*/u', $text, $matches);

        return count($matches[0] ?? []);
    }

    private function storePdfIfUploaded(Request $request, array &$data): void
    {
        if ($request->hasFile('pdf_attachment')) {
            $data['pdf_path'] = $request->file('pdf_attachment')->store('article-pdfs', 'public');
        }
    }

    private function updatePdfIfRequested(Request $request, Article $article, array &$data): void
    {
        if ($request->boolean('remove_pdf')) {
            $this->deleteUploadedPdf($article);
            $data['pdf_path'] = null;
        }

        if ($request->hasFile('pdf_attachment')) {
            $this->deleteUploadedPdf($article);
            $data['pdf_path'] = $request->file('pdf_attachment')->store('article-pdfs', 'public');
        }
    }

    private function deleteUploadedPdf(Article $article): void
    {
        if ($article->hasPdfAttachment()) {
            Storage::disk('public')->delete($article->pdf_path);
        }
    }

    private function syncLinks(Request $request, Article $article): void
    {
        $links = collect($request->input('links', []))->filter(fn($l) => filled($l['link_text'] ?? null) && filled($l['url'] ?? null))->values();
        $article->relatedLinks()->delete();
        foreach ($links as $i => $link) {
            $article->relatedLinks()->create(['link_text'=>$link['link_text'], 'url'=>$link['url'], 'sort_order'=>$i+1]);
        }
    }

    private function sendNewsletterForPublishedArticle(?Article $article): void
    {
        if (! $article || $article->status !== 'published' || ! $article->published_at || $article->newsletter_notified_at) {
            return;
        }

        NewsletterSubscriber::subscribed()
            ->orderBy('id')
            ->chunkById(50, function ($subscribers) use ($article): void {
                foreach ($subscribers as $subscriber) {
                    try {
                        Mail::to($subscriber->email)->send(new NewArticlePublishedMail($article, $subscriber));
                    } catch (Throwable $exception) {
                        Log::warning('Newsletter article email failed.', [
                            'article_id' => $article->id,
                            'subscriber_id' => $subscriber->id,
                            'message' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        $article->forceFill(['newsletter_notified_at' => now()])->save();
    }
}
