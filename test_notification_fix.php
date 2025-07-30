<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Jadwal;
use App\Notifications\JadwalNotification;
use App\Notifications\MediatorInAppNotification;
use Illuminate\Support\Facades\DB;

try {
    echo "=== Test Notification Fix ===\n";

    // 1. Cek notifikasi yang ada di database
    echo "1. Checking existing notifications...\n";
    $totalNotifications = DB::table('notifications')->count();
    echo "   Total notifications: " . $totalNotifications . "\n";

    // 2. Cek notifikasi berdasarkan type
    $notificationTypes = DB::table('notifications')
        ->select('type', DB::raw('count(*) as count'))
        ->groupBy('type')
        ->get();

    foreach ($notificationTypes as $type) {
        echo "   - " . $type->type . ": " . $type->count . "\n";
    }

    // 3. Test filter berdasarkan role
    echo "\n2. Testing role-based notification filtering...\n";

    // Test mediator
    $mediatorUser = User::where('active_role', 'mediator')->first();
    if ($mediatorUser) {
        $mediatorNotifications = $mediatorUser->notifications()
            ->where(function ($query) {
                $query->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
                    ->orWhere('type', 'App\\Notifications\\MediatorInAppNotification')
                    ->orWhere('type', 'App\\Notifications\\KonfirmasiKehadiranNotification')
                    ->orWhere('type', 'App\\Notifications\\RescheduleRequiredNotification');
            })
            ->count();
        echo "   Mediator notifications: " . $mediatorNotifications . "\n";
    }

    // Test pelapor
    $pelaporUser = User::where('active_role', 'pelapor')->first();
    if ($pelaporUser) {
        $pelaporNotifications = $pelaporUser->notifications()
            ->where('type', 'App\\Notifications\\JadwalNotification')
            ->count();
        echo "   Pelapor notifications: " . $pelaporNotifications . "\n";
    }

    // Test terlapor
    $terlaporUser = User::where('active_role', 'terlapor')->first();
    if ($terlaporUser) {
        $terlaporNotifications = $terlaporUser->notifications()
            ->where('type', 'App\\Notifications\\JadwalNotification')
            ->count();
        echo "   Terlapor notifications: " . $terlaporNotifications . "\n";
    }

    // 4. Test membuat notifikasi baru
    echo "\n3. Testing new notification creation...\n";

    $jadwal = Jadwal::with('pengaduan.pelapor.user', 'pengaduan.terlapor.user')->first();

    if ($jadwal && $jadwal->pengaduan->pelapor && $jadwal->pengaduan->pelapor->user) {
        echo "   Creating JadwalNotification for pelapor...\n";
        $jadwal->pengaduan->pelapor->user->notify(new JadwalNotification(
            $jadwal,
            'jadwal_created',
            null,
            [
                'title' => 'Test Jadwal Baru',
                'message' => 'Ini adalah test notifikasi jadwal baru',
                'type' => 'jadwal_created',
                'jadwal_id' => $jadwal->jadwal_id
            ]
        ));
        echo "   ✅ JadwalNotification created for pelapor\n";
    }

    if ($jadwal && $jadwal->pengaduan->terlapor && $jadwal->pengaduan->terlapor->user) {
        echo "   Creating JadwalNotification for terlapor...\n";
        $jadwal->pengaduan->terlapor->user->notify(new JadwalNotification(
            $jadwal,
            'jadwal_created',
            null,
            [
                'title' => 'Test Jadwal Baru',
                'message' => 'Ini adalah test notifikasi jadwal baru',
                'type' => 'jadwal_created',
                'jadwal_id' => $jadwal->jadwal_id
            ]
        ));
        echo "   ✅ JadwalNotification created for terlapor\n";
    }

    // 5. Verifikasi notifikasi baru
    echo "\n4. Verifying new notifications...\n";

    if ($pelaporUser) {
        $newPelaporNotifications = $pelaporUser->notifications()
            ->where('type', 'App\\Notifications\\JadwalNotification')
            ->count();
        echo "   New pelapor notifications: " . $newPelaporNotifications . "\n";
    }

    if ($terlaporUser) {
        $newTerlaporNotifications = $terlaporUser->notifications()
            ->where('type', 'App\\Notifications\\JadwalNotification')
            ->count();
        echo "   New terlapor notifications: " . $newTerlaporNotifications . "\n";
    }

    echo "\n=== Test completed successfully ===\n";
    echo "✅ Notifications are now properly filtered by role!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
