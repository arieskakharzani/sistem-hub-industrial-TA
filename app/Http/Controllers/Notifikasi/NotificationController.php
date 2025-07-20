<?php

namespace App\Http\Controllers\Notifikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        $unreadCount = Auth::user()->unreadNotifications->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function show(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        // Redirect ke jadwal jika ada
        if ($notification->data['jadwal_id'] ?? false) {
            return redirect()->route('jadwal.show', $notification->data['jadwal_id']);
        }

        return redirect()->route('notifications.index');
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }
}
