<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\UchContent;

class SeoSetting extends Model
{
    public const PAGE_KEYS = [
        'home' => 'Home',
        'about' => 'About',
        'services' => 'Services',
        'industries' => 'Industries',
        'projects' => 'Projects',
        'safety' => 'Safety',
        'team' => 'Team',
        'branches' => 'Branches',
        'resources' => 'Resources',
        'careers' => 'Careers',
        'contact' => 'Contact',
        'equipment' => 'Equipment',
        'videos' => 'Videos',
        'privacy-policy' => 'Privacy Policy',
        'terms' => 'Terms of Use',
        'cookie-policy' => 'Cookie Policy',
        'accessibility' => 'Accessibility Statement',
    ];

    protected $fillable = [
        'page_key',
        'page_label',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'noindex',
        'nofollow',
        'structured_data_json',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'noindex' => 'boolean',
            'nofollow' => 'boolean',
            'structured_data_json' => 'array',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function ogImageUrl(): ?string
    {
        return UchContent::imageUrl($this->og_image);
    }

    public function twitterImageUrl(): ?string
    {
        return UchContent::imageUrl($this->twitter_image);
    }
}
