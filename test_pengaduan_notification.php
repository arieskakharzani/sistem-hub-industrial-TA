<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Events\PengaduanCreated;
use App\Models\Pengaduan;
use App\Models\Pelapor;
use App\Models\Mediator;
use App\Notifications\MediatorPengaduanNotification;

try {
    echo "=== Test Pengaduan Notification ===\n";

    // 1. Cek mediator aktif
    echo "1. Checking active mediators...\n";
    $mediators = Mediator::with('user')
        ->whereHas('user', function ($query) {
            $query->where('is_active', true);
        })
        ->get();

    echo "   Found " . $mediators->count() . " active mediators\n";
    foreach ($mediators as $mediator) {
        echo "   - " . $mediator->nama_mediator . " (" . $mediator->user->email . ")\n";
    }

    // 2. Ambil pengaduan terbaru untuk test
    echo "\n2. Getting latest pengaduan for test...\n";
    $pengaduan = Pengaduan::with('pelapor')->latest()->first();

    if (!$pengaduan) {
        echo "   ❌ No pengaduan found in database\n";
        exit;
    }

    echo "   Using pengaduan: " . $pengaduan->perihal . "\n";
    echo "   From: " . $pengaduan->pelapor->nama_pelapor . "\n";

    // 3. Test trigger event
    echo "\n3. Testing PengaduanCreated event...\n";
    event(new PengaduanCreated($pengaduan));
    echo "   ✅ Event triggered successfully\n";

    // 4. Test notification langsung
    echo "\n4. Testing direct notification...\n";
    if ($mediators->count() > 0) {
        $testMediator = $mediators->first();
        echo "   Sending notification to: " . $testMediator->user->email . "\n";

        $testMediator->user->notify(new MediatorPengaduanNotification($pengaduan));
        echo "   ✅ Direct notification sent successfully\n";
    }

    // 5. Cek queue jobs
    echo "\n5. Checking queue jobs...\n";
    $jobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
    echo "   Pending jobs: " . $jobsCount . "\n";

    if ($jobsCount > 0) {
        echo "   Processing jobs...\n";
        \Illuminate\Support\Facades\Artisan::call('queue:work', ['--once' => true]);
        echo "   ✅ Jobs processed\n";
    }

    // 6. Cek notifications di database
    echo "\n6. Checking notifications in database...\n";
    $notificationsCount = \Illuminate\Support\Facades\DB::table('notifications')->count();
    echo "   Total notifications: " . $notificationsCount . "\n";

    $recentNotifications = \Illuminate\Support\Facades\DB::table('notifications')
        ->where('type', 'App\\Notifications\\MediatorPengaduanNotification')
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    foreach ($recentNotifications as $notification) {
        echo "   - " . $notification->type . " created at " . $notification->created_at . "\n";
    }

    echo "\n=== Test completed successfully ===\n";
    echo "📧 Check email inboxes for notifications!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
