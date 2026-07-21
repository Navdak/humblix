<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafetyTopic;
use App\Models\Video;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SafetyTopicController extends Controller
{
    public function index()
    {
        return view('admin.safety.index', [
            'topics' => SafetyTopic::ordered()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.safety.create', $this->formData(new SafetyTopic()));
    }

    public function store(Request $request): RedirectResponse
    {
        SafetyTopic::create($this->validated($request));

        return redirect()->route('admin.safety.index')->with('success', 'Safety topic created.');
    }

    public function edit(SafetyTopic $safety)
    {
        return view('admin.safety.edit', $this->formData($safety));
    }

    public function update(Request $request, SafetyTopic $safety): RedirectResponse
    {
        $safety->update($this->validated($request, $safety));

        return back()->with('success', 'Safety topic updated.');
    }

    public function destroy(SafetyTopic $safety): RedirectResponse
    {
        $safety->deleteUploadedImage();
        $safety->delete();

        return redirect()->route('admin.safety.index')->with('success', 'Safety topic deleted.');
    }

    private function formData(SafetyTopic $topic): array
    {
        return [
            'topic' => $topic,
            'statuses' => SafetyTopic::STATUSES,
            'videoPlacements' => SafetyTopic::VIDEO_PLACEMENTS,
        ];
    }

    private function validated(Request $request, ?SafetyTopic $topic = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('safety_topics', 'slug')->ignore($topic)],
            'category' => ['nullable', 'string', 'max:120'],
            'excerpt' => ['required', 'string', 'max:500'],
            'summary_points_text' => ['nullable', 'string', 'max:2000'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'video_title' => ['nullable', 'string', 'max:160'],
            'video_caption' => ['nullable', 'string', 'max:500'],
            'video_placement' => ['nullable', 'string', Rule::in(array_keys(SafetyTopic::VIDEO_PLACEMENTS))],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(SafetyTopic::STATUSES)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['summary_points'] = $this->summaryPointsFromText((string) ($data['summary_points_text'] ?? ''));
        $data['content'] = HtmlSanitizer::clean($data['content']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['video_placement'] = $data['video_placement'] ?: 'end';
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : ($data['published_at'] ?? null);

        $this->normalizeVideoData($data);

        if ($request->boolean('remove_image')) {
            $topic?->deleteUploadedImage();
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $topic?->deleteUploadedImage();
            $data['image_path'] = $request->file('image')->store('safety-topics', 'public');
        }

        unset($data['image'], $data['remove_image'], $data['summary_points_text']);

        return $data;
    }

    /**
     * @return list<string>
     */
    private function summaryPointsFromText(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text) ?: [])
            ->map(fn (string $line) => trim($line, " \t\n\r\0\x0B-•"))
            ->filter()
            ->take(8)
            ->values()
            ->all();
    }

    private function normalizeVideoData(array &$data): void
    {
        $videoUrl = trim((string) ($data['video_url'] ?? ''));

        if ($videoUrl === '') {
            $data['video_url'] = null;
            $data['video_embed_url'] = null;
            $data['video_title'] = null;
            $data['video_caption'] = null;

            return;
        }

        $embedUrl = $this->safeVideoEmbedUrl($videoUrl);

        if (! $embedUrl) {
            throw ValidationException::withMessages([
                'video_url' => 'Use a supported YouTube, YouTube Shorts, Vimeo, MP4, or WebM video URL.',
            ]);
        }

        $data['video_url'] = $videoUrl;
        $data['video_embed_url'] = $embedUrl;
    }

    private function safeVideoEmbedUrl(string $url): ?string
    {
        $youtubeId = Video::youtubeVideoIdFromUrl($url);

        if ($youtubeId) {
            return "https://www.youtube-nocookie.com/embed/{$youtubeId}";
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
}
