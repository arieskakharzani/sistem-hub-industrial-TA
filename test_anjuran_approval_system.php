<?php

require_once 'vendor/autoload.php';

use App\Models\Anjuran;
use App\Models\User;
use App\Models\Mediator;
use App\Models\KepalaDinas;
use App\Models\Pengaduan;
use App\Models\DokumenHubunganIndustrial;
use App\Notifications\AnjuranPendingApprovalNotification;
use App\Notifications\AnjuranApprovedNotification;
use App\Notifications\AnjuranRejectedNotification;
use App\Notifications\AnjuranPublishedNotification;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST SISTEM APPROVAL ANJURAN ===\n\n";

try {
    // 1. Cek apakah ada anjuran yang sudah ada
    $anjuran = Anjuran::with(['dokumenHI.pengaduan.mediator'])->first();

    if (!$anjuran) {
        echo "❌ Tidak ada data anjuran untuk testing\n";
        echo "Silakan buat anjuran terlebih dahulu melalui sistem\n";
        exit;
    }

    echo "✅ Data anjuran ditemukan:\n";
    echo "- ID: {$anjuran->anjuran_id}\n";
    echo "- Nomor: {$anjuran->nomor_anjuran}\n";
    echo "- Status: {$anjuran->status_approval}\n";
    echo "- Mediator: {$anjuran->mediator->nama_mediator}\n\n";

    // 2. Test method helper
    echo "=== TEST METHOD HELPER ===\n";
    echo "- isPendingApproval(): " . ($anjuran->isPendingApproval() ? 'true' : 'false') . "\n";
    echo "- isApproved(): " . ($anjuran->isApproved() ? 'true' : 'false') . "\n";
    echo "- isPublished(): " . ($anjuran->isPublished() ? 'true' : 'false') . "\n";
    echo "- canBeApprovedByKepalaDinas(): " . ($anjuran->canBeApprovedByKepalaDinas() ? 'true' : 'false') . "\n";
    echo "- canBePublishedByMediator(): " . ($anjuran->canBePublishedByMediator() ? 'true' : 'false') . "\n\n";

    // 3. Test notification classes
    echo "=== TEST NOTIFICATION CLASSES ===\n";

    // Test AnjuranPendingApprovalNotification
    try {
        $notification = new AnjuranPendingApprovalNotification($anjuran);
        echo "✅ AnjuranPendingApprovalNotification: OK\n";
    } catch (Exception $e) {
        echo "❌ AnjuranPendingApprovalNotification: " . $e->getMessage() . "\n";
    }

    // Test AnjuranApprovedNotification
    try {
        $notification = new AnjuranApprovedNotification($anjuran);
        echo "✅ AnjuranApprovedNotification: OK\n";
    } catch (Exception $e) {
        echo "❌ AnjuranApprovedNotification: " . $e->getMessage() . "\n";
    }

    // Test AnjuranRejectedNotification
    try {
        $notification = new AnjuranRejectedNotification($anjuran, "Test alasan rejection");
        echo "✅ AnjuranRejectedNotification: OK\n";
    } catch (Exception $e) {
        echo "❌ AnjuranRejectedNotification: " . $e->getMessage() . "\n";
    }

    // Test AnjuranPublishedNotification
    try {
        $notification = new AnjuranPublishedNotification($anjuran);
        echo "✅ AnjuranPublishedNotification: OK\n";
    } catch (Exception $e) {
        echo "❌ AnjuranPublishedNotification: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // 4. Test workflow (jika anjuran masih draft)
    if ($anjuran->status_approval === 'draft') {
        echo "=== TEST WORKFLOW SUBMIT ===\n";
        echo "Status anjuran: {$anjuran->status_approval}\n";
        echo "Mengubah status ke pending_kepala_dinas...\n";

        $anjuran->update(['status_approval' => 'pending_kepala_dinas']);
        echo "✅ Status berhasil diubah ke pending_kepala_dinas\n";
        echo "Status baru: {$anjuran->status_approval}\n\n";
    }

    // 5. Test workflow (jika anjuran pending approval)
    if ($anjuran->status_approval === 'pending_kepala_dinas') {
        echo "=== TEST WORKFLOW APPROVE ===\n";
        echo "Status anjuran: {$anjuran->status_approval}\n";
        echo "Mengubah status ke approved...\n";

        $anjuran->update([
            'status_approval' => 'approved',
            'approved_by_kepala_dinas_at' => now(),
            'notes_kepala_dinas' => 'Test approval dari kepala dinas'
        ]);
        echo "✅ Status berhasil diubah ke approved\n";
        echo "Status baru: {$anjuran->status_approval}\n";
        echo "Approved at: {$anjuran->approved_by_kepala_dinas_at}\n\n";
    }

    // 6. Test workflow (jika anjuran approved)
    if ($anjuran->status_approval === 'approved') {
        echo "=== TEST WORKFLOW PUBLISH ===\n";
        echo "Status anjuran: {$anjuran->status_approval}\n";
        echo "Mengubah status ke published...\n";

        $anjuran->update([
            'status_approval' => 'published',
            'published_at' => now(),
            'deadline_response_at' => now()->addDays(10)
        ]);
        echo "✅ Status berhasil diubah ke published\n";
        echo "Status baru: {$anjuran->status_approval}\n";
        echo "Published at: {$anjuran->published_at}\n";
        echo "Deadline response: {$anjuran->deadline_response_at}\n";
        echo "Days until deadline: {$anjuran->getDaysUntilDeadline()}\n\n";
    }

    // 7. Test scopes
    echo "=== TEST SCOPES ===\n";
    $pendingCount = Anjuran::pendingApproval()->count();
    $approvedCount = Anjuran::approved()->count();
    $publishedCount = Anjuran::published()->count();

    echo "- Pending approval: {$pendingCount}\n";
    echo "- Approved: {$approvedCount}\n";
    echo "- Published: {$publishedCount}\n\n";

    echo "=== TEST SELESAI ===\n";
    echo "Sistem approval anjuran berhasil diimplementasikan!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
