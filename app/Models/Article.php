<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = ['title','slug','featured_image_path','excerpt','content','status','published_at','author_id'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
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

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            if (! $article->slug) {
                $article->slug = Str::slug($article->title);
            }

            if ($article->status === 'published' && ! $article->published_at) {
                $article->published_at = now();
            }
        });
    }
}
