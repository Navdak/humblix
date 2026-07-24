<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AdminNotificationRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $visibleQuery = AdminNotification::query()
            ->visibleTo($user);

        $unreadNotifications = (clone $visibleQuery)
            ->unreadFor($user)
            ->latest()
            ->limit(10)
            ->get();

        $recentNotifications = (clone $visibleQuery)
            ->latest()
            ->limit(10)
            ->get();

        $notifications = $unreadNotifications
            ->concat($recentNotifications->reject(fn (AdminNotification $notification) => $unreadNotifications->contains('id', $notification->id)))
            ->take(15)
            ->values();

        $readIds = AdminNotificationRead::query()
            ->where('user_id', $user->id)
            ->whereIn('admin_notification_id', $notifications->pluck('id'))
            ->pluck('admin_notification_id')
            ->all();

        $unreadQuery = AdminNotification::query()
            ->visibleTo($user)
            ->unreadFor($user);

        $unreadCount = (clone $unreadQuery)->count();

        $latestId = (int) ((clone $visibleQuery)->max('id') ?? 0);

        return response()->json([
            'unread_count' => $unreadCount,
            'latest_id' => $latestId,
            'notifications' => $notifications->map(fn (AdminNotification $notification) => [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'permission' => $notification->permission,
                'action_url' => $notification->action_url,
                'created_at' => $notification->created_at?->toIso8601String(),
                'human_time' => $notification->created_at?->diffForHumans(),
                'is_unread' => ! in_array($notification->id, $readIds, true),
                'data' => $notification->data ?: [],
            ])->values(),
            'list_updates' => [
                'enquiries' => (clone $unreadQuery)->where('type', 'new_enquiry')->count(),
                'client_jobs' => (clone $unreadQuery)->where('type', 'client_job_message')->count(),
            ],
        ]);
    }

    public function markRead(Request $request, AdminNotification $notification): JsonResponse
    {
        $user = $request->user();
        abort_unless($notification->isVisibleTo($user), 403);

        AdminNotificationRead::updateOrCreate(
            ['admin_notification_id' => $notification->id, 'user_id' => $user->id],
            ['read_at' => now()],
        );

        return response()->json(['ok' => true]);
    }

    public function markModuleRead(Request $request, string $module): JsonResponse
    {
        $user = $request->user();
        $now = now();
        $types = $this->notificationTypesForModule($module);

        abort_unless($types !== [], 404);

        $notificationIds = AdminNotification::query()
            ->visibleTo($user)
            ->unreadFor($user)
            ->whereIn('type', $types)
            ->latest()
            ->limit(100)
            ->pluck('id');

        $notificationIds->each(fn (int $notificationId) => AdminNotificationRead::updateOrCreate(
            ['admin_notification_id' => $notificationId, 'user_id' => $user->id],
            ['read_at' => $now],
        ));

        return response()->json([
            'ok' => true,
            'module' => $module,
            'read_count' => $notificationIds->count(),
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $now = now();

        AdminNotification::query()
            ->visibleTo($user)
            ->unreadFor($user)
            ->latest()
            ->limit(100)
            ->pluck('id')
            ->each(fn (int $notificationId) => AdminNotificationRead::updateOrCreate(
                ['admin_notification_id' => $notificationId, 'user_id' => $user->id],
                ['read_at' => $now],
            ));

        return response()->json(['ok' => true]);
    }

    private function notificationTypesForModule(string $module): array
    {
        return match ($module) {
            'enquiries' => ['new_enquiry'],
            'reviews' => ['new_review'],
            'client_jobs' => ['client_job_message'],
            default => [],
        };
    }
}
