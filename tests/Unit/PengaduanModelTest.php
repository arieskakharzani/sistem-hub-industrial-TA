<?php

use App\Models\Pengaduan;
use Carbon\Carbon;

// Tujuan: menguji helper & accessor murni di Pengaduan tanpa DB

describe('Pengaduan Model - Unit Tests (pure logic)', function () {

    test('isAssignedTo returns true when mediator_id matches', function () {
        $p = new Pengaduan();
        $p->mediator_id = 'MED-123';
        expect($p->isAssignedTo('MED-123'))->toBeTrue();
        expect($p->isAssignedTo('MED-999'))->toBeFalse();
    });

    test('isUnassigned returns true when mediator_id is null', function () {
        $p = new Pengaduan();
        $p->mediator_id = null;
        expect($p->isUnassigned())->toBeTrue();
        $p->mediator_id = 'MED-1';
        expect($p->isUnassigned())->toBeFalse();
    });

    test('getStatusBadgeClassAttribute maps status to badge classes', function () {
        $p = new Pengaduan();
        $p->status = 'pending';
        expect($p->status_badge_class)->toBe('bg-yellow-100 text-yellow-800');
        $p->status = 'proses';
        expect($p->status_badge_class)->toBe('bg-blue-100 text-blue-800');
        $p->status = 'selesai';
        expect($p->status_badge_class)->toBe('bg-green-100 text-green-800');
        $p->status = 'unknown';
        expect($p->status_badge_class)->toBe('bg-gray-100 text-gray-800');
    });

    test('getStatusTextAttribute maps status to friendly text', function () {
        $p = new Pengaduan();
        $p->status = 'pending';
        expect($p->status_text)->toBe('Menunggu Review');
        $p->status = 'proses';
        expect($p->status_text)->toBe('Sedang Diproses');
        $p->status = 'selesai';
        expect($p->status_text)->toBe('Selesai');
        $p->status = 'anything';
        expect($p->status_text)->toBe('Status Tidak Dikenal');
    });

    test('getPerihalTextAttribute reflects perihal field', function () {
        $p = new Pengaduan();
        $p->perihal = 'Hak pesangon';
        expect($p->perihal_text)->toBe('Hak pesangon');
    });

    test('perihal_text tetap aman saat perihal null/empty', function () {
        // perihal kosong -> accessor seharusnya tidak error
        $p = new Pengaduan();
        $p->perihal = null;
        expect($p->perihal_text ?? null)->toBeNull();
        $p->perihal = '';
        expect($p->perihal_text)->toBe('');
    });
});
