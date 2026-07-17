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

        $notifications = AdminNotification::query()
            ->visibleTo($user)
            ->latest()
            ->limit(10)
            ->get();

        $readIds = AdminNotificationRead::query()
            ->where('user_id', $user->id)
            ->whereIn('admin_notification_id', $notifications->pluck('id'))
            ->pluck('admin_notification_id')
            ->all();

        $unreadQuery = AdminNotification::query()
            ->visibleTo($user)
            ->unreadFor($user);

        $unreadCount = (clone $unreadQuery)->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'latest_id' => (int) ($notifications->max('id') ?? 0),
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
}
