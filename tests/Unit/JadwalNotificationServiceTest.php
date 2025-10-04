<?php

use App\Services\JadwalNotificationService;
use App\Models\Jadwal;
use App\Models\Pengaduan;
use App\Models\Mediator;
use Carbon\Carbon;

uses(Tests\TestCase::class);

describe('JadwalNotificationService - Unit Tests (helpers/formatter)', function () {

    test('shouldSendInAppNotification only for mediator', function () {
        // Membuat objek service
        $svc = new JadwalNotificationService();
        // Memastikan hasil
        expect($svc->shouldSendInAppNotification('mediator'))->toBeTrue();
        expect($svc->shouldSendInAppNotification('pelapor'))->toBeFalse();
        expect($svc->shouldSendInAppNotification('terlapor'))->toBeFalse();
    });

    test('getNotificationChannels returns channels based on role', function () {
        // Membuat objek service
        $svc = new JadwalNotificationService();
        // Memastikan hasil
        expect($svc->getNotificationChannels('mediator'))->toBe(['mail', 'database']);
        expect($svc->getNotificationChannels('pelapor'))->toBe(['mail']);
        expect($svc->getNotificationChannels('terlapor'))->toBe(['mail']);
    });

    test('formatNotificationData returns well-structured data', function () {
        // Membekukan waktu sekarang agar uji stabil
        Carbon::setTestNow(Carbon::create(2024, 1, 10, 12, 0, 0));
        // Membuat objek jadwal dan relasi minimal untuk diuji
        $jadwal = new Jadwal();
        $jadwal->jadwal_id = 'J-1';
        $jadwal->tanggal = Carbon::create(2024, 1, 12);
        $jadwal->waktu = Carbon::create(2024, 1, 1, 9, 0, 0);
        $jadwal->tempat = 'Ruang Mediasi';
        $jadwal->status_jadwal = 'dijadwalkan';
        $jadwal->catatan_jadwal = 'Bawa dokumen lengkap';
        $pengaduan = new Pengaduan();
        $pengaduan->pengaduan_id = 'P-1';
        $pengaduan->perihal = 'Hak pesangon';
        $pengaduan->nama_terlapor = 'PT Contoh';
        $pengaduan->tanggal_laporan = Carbon::create(2024, 1, 5);
        $jadwal->setRelation('pengaduan', $pengaduan);
        $mediator = new Mediator();
        $mediator->nama_mediator = 'Budi';
        $mediator->nip = '1978';
        $jadwal->setRelation('mediator', $mediator);
        $svc = new JadwalNotificationService();
        // Menjalankan fungsi yang diuji
        $data = $svc->formatNotificationData($jadwal, 'status_updated', ['old_status' => 'dijadwalkan']);
        // Memastikan hasil
        expect($data['jadwal']['id'])->toBe('J-1');
        expect($data['jadwal']['tanggal'])->toBe('12 January 2024');
        expect($data['jadwal']['waktu'])->toBe('09:00');
        expect($data['jadwal']['tempat'])->toBe('Ruang Mediasi');
        expect($data['jadwal']['status'])->toBe('dijadwalkan');
        expect($data['jadwal']['status_label'])->toBe('Dijadwalkan');
        expect($data['jadwal']['catatan'])->toBe('Bawa dokumen lengkap');
        expect($data['pengaduan']['id'])->toBe('P-1');
        expect($data['pengaduan']['perihal'])->toBe('Hak pesangon');
        expect($data['pengaduan']['nama_terlapor'])->toBe('PT Contoh');
        expect($data['pengaduan']['tanggal_laporan'])->toBe('05 January 2024');
        expect($data['mediator']['nama'])->toBe('Budi');
        expect($data['mediator']['nip'])->toBe('1978');
        expect($data['event_type'])->toBe('status_updated');
        expect($data['event_label'])->toBe('Update Status Jadwal');
        expect($data['old_status'])->toBe('dijadwalkan');
        expect($data['old_status_label'])->toBe('Dijadwalkan');
        Carbon::setTestNow();
    });
});
