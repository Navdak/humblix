<?php

namespace App\Models;

use App\Support\HumelixLinks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class ClientJob extends Model
{
    public const STATUSES = [
        'confirmed',
        'engineer_assigned',
        'site_visit_scheduled',
        'in_progress',
        'awaiting_client',
        'completed',
        'closed',
    ];

    public const PAYMENT_STATUSES = [
        'pending',
        'part_paid',
        'paid',
        'cancelled',
    ];

    protected $fillable = [
        'enquiry_id',
        'assigned_engineer_id',
        'job_reference',
        'status',
        'portal_token',
        'portal_enabled',
        'admin_unread_count',
        'client_unread_count',
        'last_client_message_at',
        'last_admin_message_at',
        'agreed_amount',
        'currency',
        'payment_status',
        'agreement_note',
        'agreed_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'portal_enabled' => 'boolean',
            'admin_unread_count' => 'integer',
            'client_unread_count' => 'integer',
            'last_client_message_at' => 'datetime',
            'last_admin_message_at' => 'datetime',
            'agreed_amount' => 'decimal:2',
            'agreed_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ClientJob $job): void {
            if (! $job->job_reference) {
                $job->job_reference = self::generateReference();
            }

            if (! $job->portal_token) {
                $job->portal_token = self::generatePortalToken();
            }

            if (! $job->status) {
                $job->status = 'confirmed';
            }

            if (! $job->currency) {
                $job->currency = 'NGN';
            }

            if (! $job->payment_status) {
                $job->payment_status = 'pending';
            }
        });
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function assignedEngineer(): BelongsTo
    {
        return $this->belongsTo(Engineer::class, 'assigned_engineer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(JobMessage::class)->oldest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(JobMessageAttachment::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(JobMessage::class)->latestOfMany();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $prefix = "HMX-JOB-{$date}-";
        $sequence = self::where('job_reference', 'like', "{$prefix}%")->count() + 1;

        do {
            $reference = $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
            $sequence++;
        } while (self::where('job_reference', $reference)->exists());

        return $reference;
    }

    public static function generatePortalToken(): string
    {
        do {
            $token = Str::random(64);
        } while (self::where('portal_token', $token)->exists());

        return $token;
    }

    public function portalUrl(): string
    {
        return HumelixLinks::url('/client/jobs/'.$this->portal_token);
    }

    public function clientName(): string
    {
        return $this->enquiry?->full_name ?: 'Client';
    }

    public function clientContact(): string
    {
        return collect([$this->enquiry?->phone, $this->enquiry?->email])->filter()->implode(' · ');
    }

    public function statusLabel(): string
    {
        return ucwords(str_replace('_', ' ', $this->status ?: 'confirmed'));
    }

    public function paymentStatusLabel(): string
    {
        return ucwords(str_replace('_', ' ', $this->payment_status ?: 'pending'));
    }

    public function formattedAmount(): string
    {
        if ($this->agreed_amount === null) {
            return 'Not recorded';
        }

        return trim(($this->currency ?: 'NGN').' '.number_format((float) $this->agreed_amount, 2));
    }
}
