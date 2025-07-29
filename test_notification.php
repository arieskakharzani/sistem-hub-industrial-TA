<?php

require_once 'vendor/autoload.php';

use App\Models\Pengaduan;
use App\Models\Mediator;
use App\Notifications\MediatorPengaduanNotification;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Ambil pengaduan terbaru
    $pengaduan = Pengaduan::with('pelapor')->latest()->first();

    if (!$pengaduan) {
        echo "Tidak ada pengaduan di database\n";
        exit;
    }

    // Ambil mediator pertama
    $mediator = Mediator::with('user')->first();

    if (!$mediator || !$mediator->user) {
        echo "Tidak ada mediator aktif di database\n";
        exit;
    }

    echo "Testing notification untuk:\n";
    echo "- Pengaduan: " . $pengaduan->perihal . "\n";
    echo "- Mediator: " . $mediator->user->name . "\n";
    echo "- Email: " . $mediator->user->email . "\n";

    // Kirim notification
    $mediator->user->notify(new MediatorPengaduanNotification($pengaduan));

    echo "Notification berhasil dikirim!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
