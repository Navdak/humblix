<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUBSCRIBED = 'subscribed';
    public const STATUS_UNSUBSCRIBED = 'unsubscribed';

    protected $fillable = [
        'name',
        'email',
        'status',
        'confirmation_token',
        'unsubscribe_token',
        'confirmed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function scopeSubscribed(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_SUBSCRIBED)
            ->whereNotNull('confirmed_at')
            ->whereNull('unsubscribed_at');
    }

    public function isSubscribed(): bool
    {
        return $this->status === self::STATUS_SUBSCRIBED
            && filled($this->confirmed_at)
            && blank($this->unsubscribed_at);
    }

    public function refreshTokens(): void
    {
        $this->confirmation_token = Str::random(48);
        $this->unsubscribe_token = $this->unsubscribe_token ?: Str::random(48);
    }

    public function markSubscribed(): void
    {
        $this->forceFill([
            'status' => self::STATUS_SUBSCRIBED,
            'confirmed_at' => now(),
            'unsubscribed_at' => null,
            'confirmation_token' => null,
            'unsubscribe_token' => $this->unsubscribe_token ?: Str::random(48),
        ])->save();
    }

    public function markUnsubscribed(): void
    {
        $this->forceFill([
            'status' => self::STATUS_UNSUBSCRIBED,
            'unsubscribed_at' => now(),
        ])->save();
    }
}
