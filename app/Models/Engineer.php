<?php

namespace App\Models;

use App\Support\UchContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Engineer extends Model
{
    use SoftDeletes;

    public const FIELDS_OF_WORK = [
        'HVAC',
        'Solar',
        'Electrical',
        'Maintenance',
        'Vendor / Equipment',
        'Home Appliance',
        'Safety',
        'General Field Support',
    ];

    public const AVAILABILITY_STATUSES = [
        'active' => 'Active',
        'busy' => 'Busy',
        'unavailable' => 'Unavailable',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'title',
        'field_of_work',
        'phone',
        'whatsapp',
        'email',
        'region',
        'availability_status',
        'notes',
        'photo_path',
        'linked_user_id',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function linkedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }

    public function assignedEnquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'assigned_engineer_id');
    }

    public function clientJobs(): HasMany
    {
        return $this->hasMany(ClientJob::class, 'assigned_engineer_id');
    }

    public function scopeAssignable(Builder $query): Builder
    {
        return $query
            ->whereIn('availability_status', ['active', 'busy'])
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function availabilityLabel(): string
    {
        return self::AVAILABILITY_STATUSES[$this->availability_status ?: 'active'] ?? 'Active';
    }

    public function assignmentLabel(): string
    {
        return collect([
            $this->name,
            $this->field_of_work,
            $this->region,
        ])->filter()->implode(' — ');
    }

    public function contactSummary(): string
    {
        return collect([
            $this->phone ? 'Phone: '.$this->phone : null,
            $this->whatsapp ? 'WhatsApp: '.$this->whatsapp : null,
            $this->email ? 'Email: '.$this->email : null,
        ])->filter()->implode(' · ');
    }

    public function photoUrl(): string
    {
        return UchContent::imageUrl($this->photo_path, 'images/generated/careers/careers-technicians-working.jpg');
    }

    public function hasUploadedPhoto(): bool
    {
        return filled($this->photo_path) && ! str_starts_with((string) $this->photo_path, 'images/');
    }

    public function deleteUploadedPhoto(): void
    {
        if ($this->hasUploadedPhoto()) {
            Storage::disk('public')->delete($this->photo_path);
        }
    }
}
