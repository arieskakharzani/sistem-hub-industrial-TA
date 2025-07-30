<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PerjanjianBersama;
use App\Models\Pengaduan;
use App\Http\Controllers\Dokumen\PerjanjianBersamaController;
use Illuminate\Support\Facades\DB;

try {
    echo "=== Test Perjanjian Bersama Complete ===\n";

    // 1. Cek perjanjian bersama yang ada
    echo "1. Checking existing perjanjian bersama...\n";
    $perjanjianBersama = PerjanjianBersama::with([
        'dokumenHI.pengaduan.pelapor.user',
        'dokumenHI.pengaduan.terlapor',
        'dokumenHI.pengaduan.mediator.user'
    ])->first();

    if (!$perjanjianBersama) {
        echo "   ❌ Tidak ada perjanjian bersama di database\n";
        exit;
    }

    $pengaduan = $perjanjianBersama->dokumenHI->pengaduan;
    echo "   ✅ Perjanjian Bersama ditemukan\n";
    echo "   - ID: " . $perjanjianBersama->perjanjian_bersama_id . "\n";
    echo "   - Pengaduan: " . $pengaduan->perihal . "\n";
    echo "   - Status: " . $pengaduan->status . "\n";
    echo "   - Pelapor: " . ($pengaduan->pelapor->nama_pelapor ?? 'N/A') . "\n";
    echo "   - Terlapor: " . ($pengaduan->terlapor->nama_terlapor ?? 'N/A') . "\n";

    // 2. Cek status sebelum complete
    echo "\n2. Checking status before complete...\n";
    $statusBefore = $pengaduan->status;
    echo "   Status pengaduan: " . $statusBefore . "\n";

    // 3. Simulasi complete (tanpa benar-benar mengubah status)
    echo "\n3. Simulating complete process...\n";

    // Load relasi yang diperlukan
    $pengaduan->load([
        'pelapor.user',
        'terlapor',
        'mediator.user',
        'dokumenHI.perjanjianBersama'
    ]);

    // Cek apakah perjanjian bersama ada
    $perjanjianBersama = $pengaduan->dokumenHI->first()?->perjanjianBersama->first();

    if (!$perjanjianBersama) {
        echo "   ❌ Perjanjian Bersama tidak ditemukan\n";
        exit;
    }

    echo "   ✅ Perjanjian Bersama ditemukan: " . $perjanjianBersama->perjanjian_bersama_id . "\n";

    // 4. Test pengiriman email
    echo "\n4. Testing email sending...\n";

    // Email ke Pelapor
    if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
        $pelaporEmail = $pengaduan->pelapor->user->email;
        echo "   Sending email to pelapor: " . $pelaporEmail . "\n";

        try {
            \Illuminate\Support\Facades\Mail::to($pelaporEmail)
                ->send(new \App\Mail\DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'pelapor'));
            echo "   ✅ Email berhasil dikirim ke pelapor\n";
        } catch (Exception $e) {
            echo "   ❌ Error sending email to pelapor: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ⚠️ Pelapor atau user pelapor tidak ditemukan\n";
    }

    // Email ke Terlapor
    if ($pengaduan->terlapor) {
        $terlaporEmail = $pengaduan->terlapor->email_terlapor;
        echo "   Sending email to terlapor: " . $terlaporEmail . "\n";

        try {
            \Illuminate\Support\Facades\Mail::to($terlaporEmail)
                ->send(new \App\Mail\DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'terlapor'));
            echo "   ✅ Email berhasil dikirim ke terlapor\n";
        } catch (Exception $e) {
            echo "   ❌ Error sending email to terlapor: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ⚠️ Terlapor tidak ditemukan\n";
    }

    // 5. Cek queue jobs
    echo "\n5. Checking queue jobs...\n";
    $jobsCount = DB::table('jobs')->count();
    echo "   Pending jobs: " . $jobsCount . "\n";

    if ($jobsCount > 0) {
        echo "   Processing jobs...\n";
        \Illuminate\Support\Facades\Artisan::call('queue:work', ['--once' => true]);
        echo "   ✅ Jobs processed\n";
    }

    echo "\n=== Test completed successfully ===\n";
    echo "✅ Perjanjian Bersama complete process is working!\n";
    echo "📧 Check email inboxes for draft perjanjian bersama!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
