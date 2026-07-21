<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use App\Support\UchContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SafetyTopic extends Model
{
    public const STATUSES = ['draft', 'published'];

    public const VIDEO_PLACEMENTS = [
        'after_intro' => 'After intro',
        'middle' => 'Middle of topic',
        'end' => 'End of topic',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'summary_points',
        'content',
        'image_path',
        'video_url',
        'video_embed_url',
        'video_title',
        'video_caption',
        'video_placement',
        'cta_label',
        'cta_url',
        'status',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'summary_points' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function imageUrl(): string
    {
        return UchContent::imageUrl($this->image_path, UchContent::safetyImage($this->title));
    }

    public function hasUploadedImage(): bool
    {
        return filled($this->image_path) && ! str_starts_with((string) $this->image_path, 'images/');
    }

    public function deleteUploadedImage(): void
    {
        if ($this->hasUploadedImage()) {
            Storage::disk('public')->delete($this->image_path);
        }
    }

    public function hasVideo(): bool
    {
        return filled($this->video_embed_url);
    }

    public function videoPlacement(): string
    {
        return array_key_exists((string) $this->video_placement, self::VIDEO_PLACEMENTS)
            ? (string) $this->video_placement
            : 'end';
    }

    public function videoPlacementLabel(): string
    {
        return self::VIDEO_PLACEMENTS[$this->videoPlacement()];
    }

    public function videoPlaybackKind(): string
    {
        $url = strtolower((string) $this->video_embed_url);

        return Str::contains($url, ['.mp4', '.webm']) ? 'video' : 'iframe';
    }

    public function isYoutubeShort(): bool
    {
        $url = (string) $this->video_url;
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        return (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com'))
            && str_starts_with($path, 'shorts/');
    }

    public function videoPlaybackAspect(): string
    {
        return $this->isYoutubeShort() ? 'short' : 'wide';
    }

    /**
     * @return array{0:string,1:string}
     */
    public function sanitizedContentSegmentsForVideo(): array
    {
        $content = HtmlSanitizer::clean((string) $this->content);

        if (! $this->hasVideo() || $this->videoPlacement() !== 'middle') {
            return [$content, ''];
        }

        $blocks = preg_split('/(?<=<\/p>|<\/ul>|<\/ol>|<\/blockquote>|<\/h2>|<\/h3>)/i', $content, -1, PREG_SPLIT_NO_EMPTY);

        if (! is_array($blocks) || count($blocks) < 2) {
            return [$content, ''];
        }

        $splitAt = max(1, intdiv(count($blocks), 2));

        return [
            implode('', array_slice($blocks, 0, $splitAt)),
            implode('', array_slice($blocks, $splitAt)),
        ];
    }

    public function toPublicArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'image' => $this->image_path ?: UchContent::safetyImage($this->title),
            'image_url' => $this->imageUrl(),
            'description' => $this->excerpt,
            'summary' => $this->summary_points ?: [],
            'detail' => strip_tags((string) $this->content),
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (SafetyTopic $topic): void {
            if (! $topic->slug) {
                $topic->slug = Str::slug($topic->title);
            }

            if (! array_key_exists((string) $topic->video_placement, self::VIDEO_PLACEMENTS)) {
                $topic->video_placement = 'end';
            }

            if ($topic->status === 'published' && ! $topic->published_at) {
                $topic->published_at = now();
            }
        });
    }
}
