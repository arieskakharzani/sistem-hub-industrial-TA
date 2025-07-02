<?php

namespace App\Http\Controllers\Notifikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        // Hanya mediator yang bisa lihat notifikasi pengaduan
        if ($user->role !== 'mediator') {
            abort(403, 'Access denied');
        }

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        // Hitung notifikasi yang belum dibaca
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'mediator') {
            return response()->json(['count' => 0]);
        }

        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown (AJAX)
     */
    public function getRecent(): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'mediator') {
            return response()->json(['notifications' => []]);
        }

        $notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notifikasi',
                    'message' => $notification->data['message'] ?? '',
                    'action_url' => $notification->data['action_url'] ?? '#',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'type' => $notification->data['type'] ?? 'general'
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notificationId): JsonResponse
    {
        $user = Auth::user();

        $notification = $user->notifications()->find($notificationId);

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    /**
     * Delete notification
     */
    public function delete($notificationId): JsonResponse
    {
        $user = Auth::user();

        $notification = $user->notifications()->find($notificationId);

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }

    /**
     * Clear all notifications
     */
    public function clearAll(): JsonResponse
    {
        $user = Auth::user();

        $user->notifications()->delete();

        return response()->json(['success' => true, 'message' => 'All notifications cleared']);
    }
}
