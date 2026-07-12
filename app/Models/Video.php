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
        return $this->thumbnail_path ? Storage::disk('public')->url($this->thumbnail_path) : null;
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
