<?php

use App\Models\Terlapor;

// Tujuan: menguji helper murni pada Terlapor (tanpa DB)

describe('Terlapor Model - Unit Tests (pure logic)', function () {

    test('isAccountActive returns true only when has_account and is_active are true', function () {
        // Arrange: kombinasi nilai bendera akun/aktif
        $t = new Terlapor();

        $t->has_account = true;
        $t->is_active = true;
        expect($t->isAccountActive())->toBeTrue();

        $t->has_account = true;
        $t->is_active = false;
        expect($t->isAccountActive())->toBeFalse();

        $t->has_account = false;
        $t->is_active = true;
        expect($t->isAccountActive())->toBeFalse();
    });

    test('canCreateAccount returns true when account not created yet', function () {
        // Arrange: akun belum dibuat -> boleh dibuat
        $t = new Terlapor();

        $t->has_account = false;
        expect($t->canCreateAccount())->toBeTrue();

        // Change: akun sudah ada -> tidak boleh dibuat lagi
        $t->has_account = true;
        expect($t->canCreateAccount())->toBeFalse();
    });
});
