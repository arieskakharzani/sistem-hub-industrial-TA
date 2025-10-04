<?php

use App\Models\User;

describe('User Model - Unit Tests (helper methods)', function () {

    test('hasRole, hasAnyRole, hasAllRoles behave correctly', function () {
        $u = new User();
        $u->roles = ['pelapor', 'mediator'];
        expect($u->hasRole('pelapor'))->toBeTrue();
        expect($u->hasRole('admin'))->toBeFalse();
        expect($u->hasAnyRole(['admin', 'mediator']))->toBeTrue();
        expect($u->hasAnyRole(['admin', 'kepala_dinas']))->toBeFalse();
        expect($u->hasAllRoles(['pelapor', 'mediator']))->toBeTrue();
        expect($u->hasAllRoles(['pelapor', 'admin']))->toBeFalse();
    });

    test('getRole returns active role', function () {
        $u = new User();
        $u->roles = ['pelapor', 'mediator'];
        $u->active_role = 'mediator';
        expect($u->getRole())->toBe('mediator');
    });

    test('role checker helpers reflect active role', function () {
        $u = new User();
        $u->roles = ['pelapor', 'mediator', 'kepala_dinas'];
        $u->active_role = 'pelapor';
        expect($u->isPelapor())->toBeTrue();
        expect($u->isTerlapor())->toBeFalse();
        expect($u->isMediator())->toBeFalse();
        expect($u->isKepalaDinas())->toBeFalse();
        $u->active_role = 'mediator';
        expect($u->isMediator())->toBeTrue();
        $u->active_role = 'kepala_dinas';
        expect($u->isKepalaDinas())->toBeTrue();
    });

    test('roles kosong: semua pengecekan peran harus false', function () {
        // Membuat user tanpa role
        $u = new User();
        $u->roles = [];
        expect($u->hasRole('pelapor'))->toBeFalse();
        expect($u->hasAnyRole(['pelapor']))->toBeFalse();
        expect($u->hasAllRoles(['pelapor']))->toBeFalse();
    });

    test('roles duplikat tidak mempengaruhi hasil hasAllRoles', function () {
        // Membuat user dengan role duplikat
        $u = new User();
        $u->roles = ['pelapor', 'pelapor', 'mediator'];
        expect($u->hasAllRoles(['pelapor', 'mediator']))->toBeTrue();
    });
});
