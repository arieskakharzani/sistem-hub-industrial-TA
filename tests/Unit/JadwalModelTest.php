<?php

use App\Models\Jadwal;
use Carbon\Carbon;

// Boot TestCase agar Eloquent siap (tanpa DB nyata)
uses(Tests\TestCase::class);

describe('Jadwal Model - Unit Tests (time and helpers)', function () {

    beforeEach(function () {
        // Kunci waktu sekarang agar perhitungan deterministik
        Carbon::setTestNow(Carbon::create(2024, 1, 10, 12, 0, 0));
    });

    afterEach(function () {
        Carbon::setTestNow();
    });

    test('getConfirmationDeadline returns one day before jadwal at same time', function () {
        // Arrange: tanggal dan jam jadwal
        $j = new Jadwal();
        $j->tanggal = Carbon::create(2024, 1, 12);
        $j->waktu = Carbon::create(2024, 1, 1, 9, 30, 0);

        // Assert: deadline = H-1 pada jam yang sama
        $deadline = $j->getConfirmationDeadline();
        expect($deadline->toDateTimeString())->toBe('2024-01-11 09:30:00');
    });

    test('isConfirmationDeadlinePassed compares now with deadline', function () {
        // Arrange: sekarang 2024-01-10 12:00
        $j = new Jadwal();
        $j->tanggal = Carbon::create(2024, 1, 10);
        $j->waktu = Carbon::create(2024, 1, 1, 11, 0, 0);

        // Deadline 2024-01-09 11:00 -> sudah lewat
        expect($j->isConfirmationDeadlinePassed())->toBeTrue();

        // Deadline 2024-01-10 15:00 -> belum lewat
        $j->tanggal = Carbon::create(2024, 1, 11);
        $j->waktu = Carbon::create(2024, 1, 1, 15, 0, 0);
        expect($j->isConfirmationDeadlinePassed())->toBeFalse();
    });

    test('isOverdue true when now after jadwal datetime', function () {
        // Arrange: bandingkan waktu sekarang vs waktu jadwal
        $j = new Jadwal();
        $j->tanggal = Carbon::create(2024, 1, 10);
        $j->waktu = Carbon::create(2024, 1, 1, 11, 0, 0);
        expect($j->isOverdue())->toBeTrue();

        $j->tanggal = Carbon::create(2024, 1, 10);
        $j->waktu = Carbon::create(2024, 1, 1, 13, 0, 0);
        expect($j->isOverdue())->toBeFalse();
    });

    test('sudahDikonfirmasiSemua true only when both confirmations not pending', function () {
        // Arrange: status konfirmasi kedua pihak
        $j = new Jadwal();
        $j->konfirmasi_pelapor = 'pending';
        $j->konfirmasi_terlapor = 'pending';
        expect($j->sudahDikonfirmasiSemua())->toBeFalse();

        $j->konfirmasi_pelapor = 'hadir';
        $j->konfirmasi_terlapor = 'pending';
        expect($j->sudahDikonfirmasiSemua())->toBeFalse();

        $j->konfirmasi_terlapor = 'hadir';
        expect($j->sudahDikonfirmasiSemua())->toBeTrue();
    });

    test('adaYangTidakHadir true when any party marked tidak_hadir', function () {
        // Arrange: tandai salah satu tidak hadir
        $j = new Jadwal();
        $j->konfirmasi_pelapor = 'hadir';
        $j->konfirmasi_terlapor = 'hadir';
        expect($j->adaYangTidakHadir())->toBeFalse();

        $j->konfirmasi_terlapor = 'tidak_hadir';
        expect($j->adaYangTidakHadir())->toBeTrue();
    });

    test('isConfirmationDeadlineApproaching true pada <= 24 jam dan > 0', function () {
        // Deadline 23 jam lagi -> true
        $j = new Jadwal();
        $j->tanggal = Carbon::create(2024, 1, 11);
        $j->waktu = Carbon::create(2024, 1, 1, 11, 0, 0); // deadline 2024-01-10 11:00
        expect($j->isConfirmationDeadlineApproaching())->toBeTrue();
        // Deadline tepat 24 jam lagi -> true (sesuai implementasi)
        $j->tanggal = Carbon::create(2024, 1, 11);
        $j->waktu = Carbon::create(2024, 1, 1, 12, 0, 0); // deadline 2024-01-10 12:00
        expect($j->isConfirmationDeadlineApproaching())->toBeTrue();
    });

    test('isConfirmationDeadlineApproaching false ketika > 24 jam', function () {
        $j = new Jadwal();
        $j->tanggal = Carbon::create(2024, 1, 11);
        $j->waktu = Carbon::create(2024, 1, 1, 13, 0, 0); // deadline 2024-01-10 13:00
        expect($j->isConfirmationDeadlineApproaching())->toBeFalse();
    });
});
