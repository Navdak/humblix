<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\EquipmentItem;
use App\Models\Project;
use App\Models\Video;
use App\Support\UchContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class VideoController extends Controller
{
    public function index()
    {
        return view('admin.videos.index', [
            'videos' => Video::with(['project', 'branch', 'equipment'])->ordered()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.videos.create', $this->formData(new Video()));
    }

    public function store(Request $request): RedirectResponse
    {
        Video::create($this->validated($request));

        return redirect()->route('admin.videos.index')->with('success', 'Video created.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', $this->formData($video));
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $video->update($this->validated($request, $video));

        return back()->with('success', 'Video updated.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter([$video->uploaded_video_path, $video->thumbnail_path]));
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted.');
    }

    private function formData(Video $video): array
    {
        return [
            'video' => $video,
            'categories' => Video::CATEGORIES,
            'videoTypes' => Video::VIDEO_TYPES,
            'statuses' => Video::STATUSES,
            'services' => collect(UchContent::serviceDivisions())->pluck('title')->values(),
            'projects' => Project::where('status', 'published')->orderBy('title')->get(['id', 'title']),
            'branches' => Branch::orderBy('name')->get(['id', 'name', 'country']),
            'equipmentItems' => EquipmentItem::orderBy('name')->get(['id', 'name', 'category']),
        ];
    }

    private function validated(Request $request, ?Video $video = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('videos', 'slug')->ignore($video)],
            'caption' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:100', Rule::in(Video::CATEGORIES)],
            'related_service' => ['nullable', 'string', 'max:190'],
            'related_project_id' => ['nullable', 'exists:projects,id'],
            'related_branch_id' => ['nullable', 'exists:branches,id'],
            'related_equipment_id' => ['nullable', 'exists:equipment_items,id'],
            'video_type' => ['required', Rule::in(Video::VIDEO_TYPES)],
            'external_url' => ['nullable', 'url', 'max:255'],
            'uploaded_video' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:30720'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', Rule::in(Video::STATUSES)],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validator = validator($data);
        $validator->after(function (Validator $validator) use ($request, $video): void {
            if ($request->input('video_type') === 'external') {
                if (! $request->filled('external_url')) {
                    $validator->errors()->add('external_url', 'An external video URL is required for external videos.');
                } elseif (! $this->safePlaybackUrl((string) $request->input('external_url'))) {
                    $validator->errors()->add('external_url', 'Use a supported YouTube, Vimeo, MP4, or WebM video URL.');
                }
            }

            if ($request->input('video_type') === 'upload' && ! $request->hasFile('uploaded_video') && ! $video?->uploaded_video_path) {
                $validator->errors()->add('uploaded_video', 'Upload a video file for uploaded videos.');
            }
        });
        $validator->validate();

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : ($data['published_at'] ?? null);
        $data['embed_url'] = $data['video_type'] === 'external' ? $this->safePlaybackUrl((string) ($data['external_url'] ?? '')) : null;

        if ($data['video_type'] === 'external') {
            $data['uploaded_video_path'] = $video?->uploaded_video_path;
        }

        if ($request->hasFile('uploaded_video')) {
            if ($video?->uploaded_video_path) {
                Storage::disk('public')->delete($video->uploaded_video_path);
            }
            $data['uploaded_video_path'] = $request->file('uploaded_video')->store('videos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($video?->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('video-thumbnails', 'public');
        }

        unset($data['uploaded_video'], $data['thumbnail']);

        return $data;
    }

    private function safePlaybackUrl(string $url): ?string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);

        if (str_contains($host, 'youtube.com')) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            $id = $query['v'] ?? trim(str_replace('/embed/', '', $path), '/');

            return preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) ? "https://www.youtube-nocookie.com/embed/{$id}" : null;
        }

        if (str_contains($host, 'youtu.be')) {
            $id = trim($path, '/');

            return preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) ? "https://www.youtube-nocookie.com/embed/{$id}" : null;
        }

        if (str_contains($host, 'vimeo.com')) {
            $id = trim($path, '/');
            $id = explode('/', $id)[0] ?? '';

            return preg_match('/^[0-9]{6,}$/', $id) ? "https://player.vimeo.com/video/{$id}" : null;
        }

        if (preg_match('/\.(mp4|webm)(\?.*)?$/i', $url)) {
            return $url;
        }

        return null;
    }
}
