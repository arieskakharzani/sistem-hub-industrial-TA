<?php

use App\Notifications\JadwalNotification;
use App\Notifications\AnjuranPublishedNotification;
use App\Models\Jadwal;
use App\Models\Pengaduan;
use App\Models\Anjuran;
use Carbon\Carbon;

uses(Tests\TestCase::class);

describe('Notifications - Unit Tests', function () {

    test('JadwalNotification payload structure', function () {
        Carbon::setTestNow(Carbon::create(2024, 1, 10, 12, 0, 0));
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
        $pengaduan->nomor_pengaduan = 'PGD-2024-0001';
        $pengaduan->tanggal_laporan = Carbon::create(2024, 1, 5);
        $jadwal->setRelation('pengaduan', $pengaduan);
        $notif = new JadwalNotification($jadwal, 'jadwal_created', null, []);
        $array = $notif->toArray((object) []);
        expect($array)->toBeArray();
        expect($array)->toHaveKeys(['title', 'message', 'type', 'jadwal_id', 'new_status', 'icon']);
        expect($array['jadwal_id'])->toBe('J-1');
        expect($array['type'])->toBe('jadwal_created');
        Carbon::setTestNow();
    });

    test('JadwalNotification jadwal_updated menunjukkan pesan perubahan status', function () {
        // Membuat objek jadwal dengan status baru
        $jadwal = new Jadwal();
        $jadwal->jadwal_id = 'J-2';
        $jadwal->status_jadwal = 'berlangsung';
        $pengaduan = new Pengaduan();
        $pengaduan->nomor_pengaduan = 'PGD-2024-0002';
        $jadwal->setRelation('pengaduan', $pengaduan);
        // Old status diisi untuk memunculkan pesan perubahan
        $notif = new JadwalNotification($jadwal, 'jadwal_updated', 'dijadwalkan', []);
        $array = $notif->toArray((object) []);
        expect($array['type'])->toBe('jadwal_updated');
        expect($array['new_status'])->toBe('berlangsung');
        expect($array['old_status'])->toBe('dijadwalkan');
        expect($array)->toHaveKeys(['title', 'message']);
    });

    test('AnjuranPublishedNotification via contains database channel', function () {
        $anjuran = new Anjuran();
        $anjuran->anjuran_id = 'ANJ-1';
        $notif = new AnjuranPublishedNotification($anjuran);
        $channels = $notif->via((object) []);
        expect($channels)->toContain('database');
    });
});
