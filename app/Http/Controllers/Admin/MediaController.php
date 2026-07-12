<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index() { return view('admin.media.index', ['assets' => MediaAsset::latest()->paginate(24)]); }

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
        Storage::disk('public')->delete($mediaAsset->file_path);
        $mediaAsset->delete();
        return back()->with('success','Media deleted.');
    }
}
