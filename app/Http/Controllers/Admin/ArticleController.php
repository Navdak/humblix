<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index() { return view('admin.articles.index', ['articles' => Article::latest()->paginate(15)]); }
    public function create() { return view('admin.articles.create', ['article' => new Article()]); }
    public function store(Request $request): RedirectResponse
    {
        $article = Article::create($this->validated($request));
        $this->syncLinks($request, $article);
        return redirect()->route('admin.articles.index')->with('success','Article created.');
    }
    public function edit(Article $article) { return view('admin.articles.edit', ['article'=>$article->load('relatedLinks')]); }
    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->validated($request));
        $this->syncLinks($request, $article);
        return back()->with('success','Article updated.');
    }
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success','Article deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'=>['required','string','max:180'], 'slug'=>['nullable','string','max:220'], 'excerpt'=>['required','string','max:160'],
            'content'=>['required','string'], 'status'=>['required','in:draft,published'], 'featured_image'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);
        $data['content'] = HtmlSanitizer::clean($data['content']);
        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['author_id'] = auth()->id();
        if ($request->hasFile('featured_image')) $data['featured_image_path'] = $request->file('featured_image')->store('articles','public');
        unset($data['featured_image']);
        return $data;
    }

    private function syncLinks(Request $request, Article $article): void
    {
        $links = collect($request->input('links', []))->filter(fn($l) => filled($l['link_text'] ?? null) && filled($l['url'] ?? null))->values();
        $article->relatedLinks()->delete();
        foreach ($links as $i => $link) {
            $article->relatedLinks()->create(['link_text'=>$link['link_text'], 'url'=>$link['url'], 'sort_order'=>$i+1]);
        }
    }
}
