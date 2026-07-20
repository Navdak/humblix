<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    public const MAX_WORD_COUNT = 8000;

    public const CATEGORIES = [
        'general' => 'General',
        'hvac' => 'HVAC',
        'solar' => 'Solar',
        'electrical' => 'Electrical',
        'maintenance' => 'Maintenance',
        'vendor-equipment' => 'Vendor / Equipment',
        'safety' => 'Safety',
        'company-news' => 'Company News',
    ];

    public const VIDEO_PLACEMENTS = [
        'after_intro' => 'After intro',
        'middle' => 'Middle of article',
        'end' => 'End of article',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'featured_image_path',
        'pdf_path',
        'video_url',
        'video_embed_url',
        'video_title',
        'video_caption',
        'video_placement',
        'excerpt',
        'content',
        'status',
        'published_at',
        'newsletter_notified_at',
        'author_id',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'newsletter_notified_at' => 'datetime',
        ];
    }

    public function relatedLinks(): HasMany
    {
        return $this->hasMany(RelatedLink::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeCategory($query, ?string $category)
    {
        if (! $category || ! array_key_exists($category, self::CATEGORIES)) {
            return $query;
        }

        return $query->where('category', $category);
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category ?: 'general'] ?? self::CATEGORIES['general'];
    }

    public function categorySlug(): string
    {
        return array_key_exists((string) $this->category, self::CATEGORIES) ? (string) $this->category : 'general';
    }

    public function hasPdfAttachment(): bool
    {
        return filled($this->pdf_path);
    }

    public function pdfUrl(): ?string
    {
        return $this->hasPdfAttachment() ? Storage::disk('public')->url($this->pdf_path) : null;
    }

    public function hasArticleVideo(): bool
    {
        return filled($this->video_embed_url);
    }

    public function articleVideoPlacement(): string
    {
        return array_key_exists((string) $this->video_placement, self::VIDEO_PLACEMENTS)
            ? (string) $this->video_placement
            : 'end';
    }

    public function articleVideoPlacementLabel(): string
    {
        return self::VIDEO_PLACEMENTS[$this->articleVideoPlacement()];
    }

    public function articleVideoPlaybackKind(): string
    {
        $url = strtolower((string) $this->video_embed_url);

        return Str::contains($url, ['.mp4', '.webm']) ? 'video' : 'iframe';
    }

    /**
     * Returns sanitized article HTML split around a sensible midpoint for embedded video placement.
     *
     * @return array{0:string,1:string}
     */
    public function sanitizedContentSegmentsForVideo(): array
    {
        $content = \App\Support\HtmlSanitizer::clean((string) $this->content);

        if (! $this->hasArticleVideo() || $this->articleVideoPlacement() !== 'middle') {
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

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            if (! array_key_exists((string) $article->category, self::CATEGORIES)) {
                $article->category = 'general';
            }

            if (! array_key_exists((string) $article->video_placement, self::VIDEO_PLACEMENTS)) {
                $article->video_placement = 'end';
            }

            if (! $article->slug) {
                $article->slug = Str::slug($article->title);
            }

            if ($article->status === 'published' && ! $article->published_at) {
                $article->published_at = now();
            }
        });
    }
}
