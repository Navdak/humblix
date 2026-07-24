<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobMessage extends Model
{
    public const VISIBILITIES = ['client', 'internal'];
    public const SENDER_TYPES = ['client', 'admin', 'engineer', 'system'];

    protected $fillable = [
        'client_job_id',
        'sender_type',
        'user_id',
        'sender_name',
        'body',
        'visibility',
        'read_by_admin_at',
        'read_by_client_at',
    ];

    protected function casts(): array
    {
        return [
            'read_by_admin_at' => 'datetime',
            'read_by_client_at' => 'datetime',
        ];
    }

    public function clientJob(): BelongsTo
    {
        return $this->belongsTo(ClientJob::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(JobMessageAttachment::class);
    }

    public function isClientVisible(): bool
    {
        return $this->visibility === 'client';
    }

    public function senderLabel(): string
    {
        return $this->sender_name ?: ucwords(str_replace('_', ' ', $this->sender_type));
    }
}
