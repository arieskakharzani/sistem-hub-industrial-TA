<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Mediator;
use App\Notifications\MediatorPengaduanNotification;
use App\Models\Pengaduan;

try {
    echo "=== Test Email SMTP Configuration ===\n";

    // Test 1: Cek konfigurasi mail
    echo "1. Checking mail configuration...\n";
    $mailConfig = config('mail');
    echo "   Default mailer: " . $mailConfig['default'] . "\n";
    echo "   From address: " . $mailConfig['from']['address'] . "\n";
    echo "   From name: " . $mailConfig['from']['name'] . "\n";

    // Test 2: Cek SMTP configuration
    echo "\n2. Checking SMTP configuration...\n";
    $smtpConfig = $mailConfig['mailers']['smtp'];
    echo "   Host: " . $smtpConfig['host'] . "\n";
    echo "   Port: " . $smtpConfig['port'] . "\n";
    echo "   Username: " . ($smtpConfig['username'] ? 'Set' : 'Not set') . "\n";
    echo "   Password: " . ($smtpConfig['password'] ? 'Set' : 'Not set') . "\n";

    // Test 3: Cek apakah ada mediator aktif
    echo "\n3. Checking active mediators...\n";
    $mediators = Mediator::with('user')
        ->whereHas('user', function ($query) {
            $query->where('is_active', true);
        })
        ->get();

    echo "   Found " . $mediators->count() . " active mediators\n";

    foreach ($mediators as $mediator) {
        echo "   - " . $mediator->nama_mediator . " (" . $mediator->user->email . ")\n";
    }

    // Test 4: Cek pengaduan terbaru
    echo "\n4. Checking latest pengaduan...\n";
    $pengaduan = Pengaduan::with('pelapor')->latest()->first();

    if ($pengaduan) {
        echo "   Latest pengaduan: " . $pengaduan->perihal . "\n";
        echo "   From: " . $pengaduan->pelapor->nama_pelapor . "\n";

        // Test 5: Kirim test notification
        echo "\n5. Sending test notification...\n";
        if ($mediators->count() > 0) {
            $testMediator = $mediators->first();
            echo "   Sending to: " . $testMediator->user->email . "\n";

            $testMediator->user->notify(new MediatorPengaduanNotification($pengaduan));
            echo "   ✅ Notification sent successfully!\n";
        } else {
            echo "   ❌ No active mediators found\n";
        }
    } else {
        echo "   ❌ No pengaduan found in database\n";
    }

    echo "\n=== Test completed ===\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
