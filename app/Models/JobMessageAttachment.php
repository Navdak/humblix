<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMessageAttachment extends Model
{
    public const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    protected $fillable = [
        'client_job_id',
        'job_message_id',
        'sender_type',
        'disk',
        'file_path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
        ];
    }

    public function clientJob(): BelongsTo
    {
        return $this->belongsTo(ClientJob::class);
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(JobMessage::class, 'job_message_id');
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function formattedSize(): string
    {
        if ($this->size_bytes >= 1048576) {
            return number_format($this->size_bytes / 1048576, 1).' MB';
        }

        if ($this->size_bytes >= 1024) {
            return number_format($this->size_bytes / 1024, 1).' KB';
        }

        return $this->size_bytes.' B';
    }
}
