<?php

use App\Models\Anjuran;
use Carbon\Carbon;

// Boot TestCase untuk menyiapkan environment tanpa DB
uses(Tests\TestCase::class);

describe('Anjuran Model - Unit Tests (status and response helpers)', function () {

    beforeEach(function () {
        // Kunci waktu sekarang agar perhitungan deterministik
        Carbon::setTestNow(Carbon::create(2024, 1, 10, 12, 0, 0));
    });

    afterEach(function () {
        Carbon::setTestNow();
    });

    test('status helpers reflect status_approval correctly', function () {
        // Arrange: uji helper boolean berdasarkan status_approval
        $a = new Anjuran();

        $a->status_approval = 'pending_kepala_dinas';
        expect($a->isPendingApproval())->toBeTrue();
        expect($a->canBeApprovedByKepalaDinas())->toBeTrue();
        expect($a->isApproved())->toBeFalse();

        $a->status_approval = 'approved';
        expect($a->isApproved())->toBeTrue();
        expect($a->canBePublishedByMediator())->toBeTrue();
        expect($a->isPendingApproval())->toBeFalse();

        $a->status_approval = 'published';
        expect($a->isPublished())->toBeTrue();
    });

    test('getDaysUntilDeadline returns non-negative days until deadline', function () {
        // Arrange: deadline 12 Jan, sekarang 10 Jan siang -> selisih 1 hari penuh
        $a = new Anjuran();
        $a->deadline_response_at = Carbon::create(2024, 1, 12);
        expect($a->getDaysUntilDeadline())->toBe(1);

        // Deadline lewat -> hasil 0 (tidak negatif)
        $a->deadline_response_at = Carbon::create(2024, 1, 8);
        expect($a->getDaysUntilDeadline())->toBe(0);
    });

    test('response helpers reflect party responses', function () {
        // Arrange: cek respon kedua pihak
        $a = new Anjuran();

        $a->response_pelapor = 'pending';
        $a->response_terlapor = 'pending';
        expect($a->hasPelaporResponded())->toBeFalse();
        expect($a->hasTerlaporResponded())->toBeFalse();
        expect($a->bothPartiesResponded())->toBeFalse();

        $a->response_pelapor = 'setuju';
        $a->response_terlapor = 'tidak_setuju';
        expect($a->hasPelaporResponded())->toBeTrue();
        expect($a->hasTerlaporResponded())->toBeTrue();
        expect($a->bothPartiesResponded())->toBeTrue();
    });

    test('isResponseDeadlinePassed and canStillRespond depend on deadline', function () {
        // Arrange: cek sebelum & sesudah deadline
        $a = new Anjuran();
        $a->deadline_response_at = Carbon::create(2024, 1, 11, 11, 0, 0);
        expect($a->isResponseDeadlinePassed())->toBeFalse();
        expect($a->canStillRespond())->toBeTrue();

        $a->deadline_response_at = Carbon::create(2024, 1, 9, 11, 0, 0);
        expect($a->isResponseDeadlinePassed())->toBeTrue();
        expect($a->canStillRespond())->toBeFalse();
    });

    test('overall response status helpers', function () {
        // Arrange: uji flag status gabungan respon kedua pihak
        $a = new Anjuran();

        $a->overall_response_status = 'both_agree';
        expect($a->isBothPartiesAgree())->toBeTrue();
        expect($a->isMixedResponse())->toBeFalse();

        $a->overall_response_status = 'both_disagree';
        expect($a->isBothPartiesDisagree())->toBeTrue();

        $a->overall_response_status = 'mixed';
        expect($a->isMixedResponse())->toBeTrue();
    });

    test('isResponseComplete true when both responded and before deadline', function () {
        // Arrange: kedua pihak sudah respon dan deadline belum lewat
        $a = new Anjuran();
        $a->response_pelapor = 'setuju';
        $a->response_terlapor = 'tidak_setuju';
        $a->deadline_response_at = Carbon::create(2024, 1, 12);
        expect($a->isResponseComplete())->toBeTrue();

        // Deadline lewat -> tidak complete
        $a->deadline_response_at = Carbon::create(2024, 1, 9);
        expect($a->isResponseComplete())->toBeFalse();
    });

    test('canCreatePerjanjianBersama true only when both agree', function () {
        // Arrange: hanya ketika kedua pihak setuju
        $a = new Anjuran();
        $a->overall_response_status = 'both_agree';
        expect($a->canCreatePerjanjianBersama())->toBeTrue();

        $a->overall_response_status = 'mixed';
        expect($a->canCreatePerjanjianBersama())->toBeFalse();
    });

    test('canFinalizeCase true when response complete and one of final states', function () {
        // Arrange: complete dan salah satu kondisi final -> true; jika lewat deadline -> false
        $a = new Anjuran();
        $a->deadline_response_at = Carbon::create(2024, 1, 12);
        $a->response_pelapor = 'setuju';
        $a->response_terlapor = 'setuju';
        $a->overall_response_status = 'both_agree';
        expect($a->canFinalizeCase())->toBeTrue();

        $a->overall_response_status = 'mixed';
        expect($a->canFinalizeCase())->toBeTrue();

        $a->deadline_response_at = Carbon::create(2024, 1, 9);
        expect($a->canFinalizeCase())->toBeFalse();
    });
});
