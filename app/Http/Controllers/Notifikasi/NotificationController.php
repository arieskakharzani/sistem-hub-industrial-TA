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
        $user = Auth::user();
        $userRole = $user->active_role;

        // Filter notifikasi berdasarkan role user
        $notifications = $user->notifications()
            ->where(function ($query) use ($userRole) {
                if ($userRole === 'mediator') {
                    // Mediator bisa lihat semua notifikasi yang ditujukan ke mediator
                    $query->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
                        ->orWhere('type', 'App\\Notifications\\MediatorInAppNotification')
                        ->orWhere('type', 'App\\Notifications\\KonfirmasiKehadiranNotification')
                        ->orWhere('type', 'App\\Notifications\\RescheduleRequiredNotification');
                } elseif ($userRole === 'pelapor') {
                    // Pelapor hanya bisa lihat notifikasi jadwal yang ditujukan ke pelapor
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                } elseif ($userRole === 'terlapor') {
                    // Terlapor hanya bisa lihat notifikasi jadwal yang ditujukan ke terlapor
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                }
            })
            ->paginate(10);

        $unreadCount = $user->unreadNotifications()
            ->where(function ($query) use ($userRole) {
                if ($userRole === 'mediator') {
                    $query->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
                        ->orWhere('type', 'App\\Notifications\\MediatorInAppNotification')
                        ->orWhere('type', 'App\\Notifications\\KonfirmasiKehadiranNotification')
                        ->orWhere('type', 'App\\Notifications\\RescheduleRequiredNotification');
                } elseif ($userRole === 'pelapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                } elseif ($userRole === 'terlapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                }
            })
            ->count();

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

    public function getUnreadCount()
    {
        $user = Auth::user();
        $userRole = $user->active_role;

        $count = $user->unreadNotifications()
            ->where(function ($query) use ($userRole) {
                if ($userRole === 'mediator') {
                    $query->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
                        ->orWhere('type', 'App\\Notifications\\MediatorInAppNotification')
                        ->orWhere('type', 'App\\Notifications\\KonfirmasiKehadiranNotification')
                        ->orWhere('type', 'App\\Notifications\\RescheduleRequiredNotification');
                } elseif ($userRole === 'pelapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                } elseif ($userRole === 'terlapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                }
            })
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getRecent()
    {
        $user = Auth::user();
        $userRole = $user->active_role;

        $notifications = $user->notifications()
            ->where(function ($query) use ($userRole) {
                if ($userRole === 'mediator') {
                    $query->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
                        ->orWhere('type', 'App\\Notifications\\MediatorInAppNotification')
                        ->orWhere('type', 'App\\Notifications\\KonfirmasiKehadiranNotification')
                        ->orWhere('type', 'App\\Notifications\\RescheduleRequiredNotification');
                } elseif ($userRole === 'pelapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                } elseif ($userRole === 'terlapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                }
            })
            ->limit(5)
            ->get();

        return response()->json($notifications);
    }

    public function delete(DatabaseNotification $notification)
    {
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }

    public function clearAll()
    {
        $user = Auth::user();
        $userRole = $user->active_role;

        $user->notifications()
            ->where(function ($query) use ($userRole) {
                if ($userRole === 'mediator') {
                    $query->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
                        ->orWhere('type', 'App\\Notifications\\MediatorInAppNotification')
                        ->orWhere('type', 'App\\Notifications\\KonfirmasiKehadiranNotification')
                        ->orWhere('type', 'App\\Notifications\\RescheduleRequiredNotification');
                } elseif ($userRole === 'pelapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                } elseif ($userRole === 'terlapor') {
                    $query->where('type', 'App\\Notifications\\JadwalNotification');
                }
            })
            ->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus');
    }
}
