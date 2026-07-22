<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $assets = MediaAsset::latest()->paginate(24);

        $assets->getCollection()->transform(function (MediaAsset $asset) {
            $asset->setAttribute('article_usage_count', $this->articleUsageCount($asset));
            $asset->setAttribute('file_exists', Storage::disk('public')->exists($asset->file_path));

            return $asset;
        });

        return view('admin.media.index', ['assets' => $assets]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['file'=>['required','file','mimes:jpg,jpeg,png,webp,pdf,mp4,mov,webm','max:10240'], 'alt_text'=>['nullable','string','max:180']]);
        $file = $request->file('file');
        $path = $file->store('media','public');
        MediaAsset::create(['file_name'=>$file->getClientOriginalName(), 'file_path'=>$path, 'mime_type'=>$file->getMimeType(), 'size_bytes'=>$file->getSize(), 'alt_text'=>$data['alt_text'] ?? null, 'uploaded_by'=>auth()->id()]);
        return back()->with('success','File uploaded.');
    }

    public function destroy(MediaAsset $mediaAsset): RedirectResponse
    {
        if ($this->articleUsageCount($mediaAsset) > 0) {
            return back()->withErrors([
                'media' => 'This media file is still used inside an article. Remove it from the article content before deleting it from the Media Library.',
            ]);
        }

        Storage::disk('public')->delete($mediaAsset->file_path);
        $mediaAsset->delete();
        return back()->with('success','Media deleted.');
    }

    private function articleUsageCount(MediaAsset $asset): int
    {
        return Article::query()
            ->where('content', 'like', '%'.$asset->file_path.'%')
            ->count();
    }
}
