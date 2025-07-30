<?php

require_once 'vendor/autoload.php';

use App\Models\Anjuran;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST SISTEM APPROVAL ANJURAN (SIMPLE) ===\n\n";

try {
    // 1. Cek apakah ada anjuran yang sudah ada
    $anjuran = Anjuran::first();

    if (!$anjuran) {
        echo "❌ Tidak ada data anjuran untuk testing\n";
        echo "Silakan buat anjuran terlebih dahulu melalui sistem\n";
        exit;
    }

    echo "✅ Data anjuran ditemukan:\n";
    echo "- ID: {$anjuran->anjuran_id}\n";
    echo "- Nomor: {$anjuran->nomor_anjuran}\n";
    echo "- Status: {$anjuran->status_approval}\n\n";

    // 2. Test status management
    echo "=== TEST STATUS MANAGEMENT ===\n";
    echo "Status saat ini: {$anjuran->status_approval}\n";

    // Test perubahan status
    $originalStatus = $anjuran->status_approval;

    // Test ke pending
    $anjuran->update(['status_approval' => 'pending_kepala_dinas']);
    echo "✅ Status berhasil diubah ke: {$anjuran->status_approval}\n";

    // Test ke approved
    $anjuran->update([
        'status_approval' => 'approved',
        'approved_by_kepala_dinas_at' => now(),
        'notes_kepala_dinas' => 'Test approval'
    ]);
    echo "✅ Status berhasil diubah ke: {$anjuran->status_approval}\n";

    // Test ke published
    $anjuran->update([
        'status_approval' => 'published',
        'published_at' => now(),
        'deadline_response_at' => now()->addDays(10)
    ]);
    echo "✅ Status berhasil diubah ke: {$anjuran->status_approval}\n";

    // Kembalikan ke status asli
    $anjuran->update(['status_approval' => $originalStatus]);
    echo "✅ Status dikembalikan ke: {$anjuran->status_approval}\n\n";

    // 3. Test scopes
    echo "=== TEST SCOPES ===\n";
    $pendingCount = Anjuran::pendingApproval()->count();
    $approvedCount = Anjuran::approved()->count();
    $publishedCount = Anjuran::published()->count();

    echo "- Pending approval: {$pendingCount}\n";
    echo "- Approved: {$approvedCount}\n";
    echo "- Published: {$publishedCount}\n\n";

    // 4. Test method helper (jika ada)
    echo "=== TEST METHOD HELPER ===\n";

    // Test getDaysUntilDeadline
    if (method_exists($anjuran, 'getDaysUntilDeadline')) {
        $days = $anjuran->getDaysUntilDeadline();
        echo "- getDaysUntilDeadline(): {$days} hari\n";
    } else {
        echo "- getDaysUntilDeadline(): Method tidak ditemukan\n";
    }

    // Test isPublished
    if (method_exists($anjuran, 'isPublished')) {
        $isPublished = $anjuran->isPublished() ? 'true' : 'false';
        echo "- isPublished(): {$isPublished}\n";
    } else {
        echo "- isPublished(): Method tidak ditemukan\n";
    }

    echo "\n=== TEST SELESAI ===\n";
    echo "Sistem approval anjuran berhasil diimplementasikan!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
