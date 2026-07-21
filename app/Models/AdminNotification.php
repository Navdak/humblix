<?php

namespace App\Models;

use App\Support\AdminPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'permission',
        'action_url',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function reads(): HasMany
    {
        return $this->hasMany(AdminNotificationRead::class);
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($user): void {
            $query->whereNull('permission')
                ->orWhereIn('permission', $user->adminPermissions());
        });
    }

    public function scopeUnreadFor(Builder $query, User $user): Builder
    {
        return $query->whereDoesntHave('reads', fn (Builder $query) => $query->where('user_id', $user->id));
    }

    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    public function isVisibleTo(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return blank($this->permission) || in_array(AdminPermissions::normalize((string) $this->permission), $user->adminPermissions(), true);
    }

    public static function createForEnquiry(Enquiry $enquiry): void
    {
        if (! self::notificationsTableIsReady()) {
            return;
        }

        self::create([
            'type' => 'new_enquiry',
            'title' => 'New enquiry received',
            'message' => trim($enquiry->full_name.' submitted a '.$enquiry->display_type_of_work.' request.'),
            'permission' => 'enquiries',
            'action_url' => route('admin.enquiries.show', $enquiry, false),
            'data' => [
                'module' => 'enquiries',
                'enquiry_id' => $enquiry->id,
                'reference_number' => $enquiry->reference_number,
                'source' => $enquiry->source,
            ],
        ]);
    }

    public static function createForReview(Review $review): void
    {
        if (! self::notificationsTableIsReady()) {
            return;
        }

        self::create([
            'type' => 'new_review',
            'title' => 'New review awaiting approval',
            'message' => trim($review->client_name.' submitted a '.$review->rating.'-star review.'),
            'permission' => 'reviews',
            'action_url' => route('admin.reviews.index', [], false),
            'data' => [
                'module' => 'reviews',
                'review_id' => $review->id,
                'rating' => $review->rating,
            ],
        ]);
    }

    private static function notificationsTableIsReady(): bool
    {
        try {
            return Schema::hasTable('admin_notifications');
        } catch (\Throwable) {
            return false;
        }
    }
}
