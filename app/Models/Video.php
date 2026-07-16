<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Video extends Model
{
    public const CATEGORIES = [
        'Field Work',
        'Projects',
        'HVAC',
        'Solar',
        'Electrical',
        'Maintenance',
        'Vendor / Equipment',
        'Home Appliance',
        'Safety',
        'Team',
        'Branches',
        'Client Work',
        'Product Demo',
    ];

    public const VIDEO_TYPES = ['external', 'upload'];
    public const STATUSES = ['draft', 'published'];

    protected $fillable = [
        'title',
        'slug',
        'caption',
        'description',
        'category',
        'related_service',
        'related_project_id',
        'related_branch_id',
        'related_equipment_id',
        'video_type',
        'external_url',
        'embed_url',
        'uploaded_video_path',
        'thumbnail_path',
        'status',
        'is_featured',
        'sort_order',
        'seo_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'related_project_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'related_branch_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class, 'related_equipment_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->latest('published_at')->latest();
    }

    public function isExternal(): bool
    {
        return $this->video_type === 'external';
    }

    public function isUploaded(): bool
    {
        return $this->video_type === 'upload';
    }

    public function playbackUrl(): ?string
    {
        if ($this->isUploaded() && $this->uploaded_video_path) {
            return Storage::disk('public')->url($this->uploaded_video_path);
        }

        return $this->embed_url ?: $this->external_url;
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail_path) {
            return Storage::disk('public')->url($this->thumbnail_path);
        }

        return $this->youtubeThumbnailUrl();
    }

    public function youtubeThumbnailUrl(): ?string
    {
        $id = $this->youtubeVideoId();

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public function youtubeVideoId(): ?string
    {
        foreach (array_filter([$this->external_url, $this->embed_url]) as $url) {
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

            if (str_contains($host, 'youtu.be')) {
                $id = strtok($path, '/');
            } elseif (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
                parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
                $id = $query['v'] ?? null;

                if (! $id && str_starts_with($path, 'embed/')) {
                    $id = substr($path, strlen('embed/'));
                    $id = strtok($id, '/');
                }

                if (! $id && str_starts_with($path, 'shorts/')) {
                    $id = substr($path, strlen('shorts/'));
                    $id = strtok($id, '/');
                }
            } else {
                $id = null;
            }

            if (is_string($id) && preg_match('/^[A-Za-z0-9_-]{6,}$/', $id)) {
                return $id;
            }
        }

        return null;
    }

    public function categoryLabel(): string
    {
        return $this->category ?: 'Video';
    }

    public function playbackKind(): string
    {
        if ($this->isUploaded()) {
            return 'video';
        }

        $url = strtolower((string) $this->playbackUrl());

        return Str::contains($url, ['.mp4', '.webm']) ? 'video' : 'iframe';
    }

    protected static function booted(): void
    {
        static::saving(function (Video $video): void {
            if (! $video->slug) {
                $video->slug = Str::slug($video->title);
            }
        });
    }
}
